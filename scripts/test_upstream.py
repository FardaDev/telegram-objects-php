#!/usr/bin/env python3
"""
Unit tests for the upstream tracking functionality.

Run with: python -m unittest scripts.test_upstream
"""

import json
import os
import tempfile
import unittest
from pathlib import Path
from unittest.mock import Mock, patch, MagicMock

# Import the module we're testing
import sys
sys.path.insert(0, os.path.dirname(__file__))

try:
    from check_upstream import UpstreamTracker
except ImportError:
    # Handle the case where the module name has hyphens
    import importlib.util
    spec = importlib.util.spec_from_file_location("check_upstream", "scripts/check-upstream.py")
    check_upstream = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(check_upstream)
    UpstreamTracker = check_upstream.UpstreamTracker


class TestUpstreamTracker(unittest.TestCase):
    """Test cases for the UpstreamTracker class."""
    
    def setUp(self):
        """Set up test fixtures before each test method."""
        # Create a temporary directory for test files
        self.test_dir = tempfile.mkdtemp()
        self.config_path = Path(self.test_dir) / "test_upstream.json"
        
        # Sample configuration data
        self.sample_config = {
            "repository": {
                "name": "defstudio/telegraph",
                "url": "https://github.com/defstudio/telegraph",
                "local_path": "vendor_sources/telegraph"
            },
            "tracking": {
                "last_checked": None,
                "current_commit": "abc123456789",
                "last_sync_commit": "abc123456789",
                "created": "2025-11-07"
            },
            "files": {
                "extracted_count": 118,
                "last_extraction_date": "2025-11-07",
                "categories": {
                    "dto_classes": 45,
                    "dto_tests": 45,
                    "enums": 5,
                    "exceptions": 11,
                    "contracts": 3,
                    "keyboards": 4,
                    "support": 5
                }
            },
            "sync_status": {
                "up_to_date": True,
                "pending_changes": [],
                "conflicts": []
            }
        }
        
        # Write the sample config to the test file
        with open(self.config_path, 'w') as f:
            json.dump(self.sample_config, f, indent=2)
    
    def tearDown(self):
        """Clean up after each test method."""
        # Remove test files
        if self.config_path.exists():
            self.config_path.unlink()
        os.rmdir(self.test_dir)
    
    def test_load_config_success(self):
        """Test successful configuration loading."""
        tracker = UpstreamTracker(str(self.config_path))
        
        self.assertEqual(tracker.config["repository"]["name"], "defstudio/telegraph")
        self.assertEqual(tracker.config["tracking"]["current_commit"], "abc123456789")
        self.assertEqual(tracker.config["files"]["extracted_count"], 118)
    
    def test_load_config_file_not_found(self):
        """Test configuration loading with missing file."""
        non_existent_path = Path(self.test_dir) / "missing.json"
        
        with self.assertRaises(FileNotFoundError):
            UpstreamTracker(str(non_existent_path))
    
    def test_load_config_invalid_json(self):
        """Test configuration loading with invalid JSON."""
        invalid_config_path = Path(self.test_dir) / "invalid.json"
        with open(invalid_config_path, 'w') as f:
            f.write("{ invalid json }")
        
        with self.assertRaises(json.JSONDecodeError):
            UpstreamTracker(str(invalid_config_path))
        
        invalid_config_path.unlink()
    
    def test_save_config(self):
        """Test configuration saving."""
        tracker = UpstreamTracker(str(self.config_path))
        
        # Modify the config
        tracker.config["tracking"]["current_commit"] = "new123456789"
        tracker._save_config()
        
        # Reload and verify
        with open(self.config_path, 'r') as f:
            saved_config = json.load(f)
        
        self.assertEqual(saved_config["tracking"]["current_commit"], "new123456789")
    
    @patch('git.Repo')
    @patch('pathlib.Path.exists')
    def test_get_repo_success(self, mock_exists, mock_repo_class):
        """Test successful repository access."""
        # Mock the repository
        mock_repo = Mock()
        mock_repo_class.return_value = mock_repo
        mock_exists.return_value = True
        
        tracker = UpstreamTracker(str(self.config_path))
        repo = tracker._get_repo()
        self.assertEqual(repo, mock_repo)
    
    def test_get_repo_path_not_found(self):
        """Test repository access with missing path."""
        tracker = UpstreamTracker(str(self.config_path))
        
        # Mock the repo_path.exists() method to return False
        with patch.object(Path, 'exists', return_value=False):
            with self.assertRaises(FileNotFoundError):
                tracker._get_repo()
    
    @patch('git.Repo')
    @patch('pathlib.Path.exists')
    def test_get_repo_invalid_repository(self, mock_exists, mock_repo_class):
        """Test repository access with invalid git repository."""
        from git import InvalidGitRepositoryError
        
        # Mock the repository to raise an exception
        mock_repo_class.side_effect = InvalidGitRepositoryError("Invalid repo")
        mock_exists.return_value = True
        
        tracker = UpstreamTracker(str(self.config_path))
        
        with self.assertRaises(ValueError):
            tracker._get_repo()
    
    @patch('git.Repo')
    @patch('pathlib.Path.exists')
    def test_get_current_commit(self, mock_exists, mock_repo_class):
        """Test getting current commit hash."""
        # Mock the repository and commit
        mock_commit = Mock()
        mock_commit.hexsha = "def456789012"
        mock_repo = Mock()
        mock_repo.head.commit = mock_commit
        mock_repo_class.return_value = mock_repo
        mock_exists.return_value = True
        
        tracker = UpstreamTracker(str(self.config_path))
        commit_hash = tracker.get_current_commit()
        self.assertEqual(commit_hash, "def456789012")
    
    @patch('git.Repo')
    @patch('pathlib.Path.exists')
    def test_check_for_updates_no_updates(self, mock_exists, mock_repo_class):
        """Test checking for updates when repository is up to date."""
        # Mock the repository
        mock_commit = Mock()
        mock_commit.hexsha = "abc123456789"  # Same as in config
        mock_repo = Mock()
        mock_repo.head.commit = mock_commit
        mock_repo.remotes.origin.fetch = Mock()
        mock_repo_class.return_value = mock_repo
        mock_exists.return_value = True
        
        tracker = UpstreamTracker(str(self.config_path))
        has_updates, new_commit, commits = tracker.check_for_updates()
        
        self.assertFalse(has_updates)
        self.assertIsNone(new_commit)
        self.assertEqual(commits, [])
    
    @patch('git.Repo')
    @patch('pathlib.Path.exists')
    def test_check_for_updates_with_updates(self, mock_exists, mock_repo_class):
        """Test checking for updates when new commits are available."""
        # Mock commits
        mock_commit1 = Mock()
        mock_commit1.hexsha = "new123456789"
        mock_commit1.message = "New feature added"
        
        mock_commit2 = Mock()
        mock_commit2.hexsha = "new987654321"
        mock_commit2.message = "Bug fix"
        
        # Mock the repository
        mock_repo = Mock()
        mock_repo.head.commit.hexsha = "new123456789"  # Different from config
        mock_repo.remotes.origin.fetch = Mock()
        mock_repo.iter_commits.return_value = [mock_commit1, mock_commit2]
        mock_repo_class.return_value = mock_repo
        mock_exists.return_value = True
        
        tracker = UpstreamTracker(str(self.config_path))
        has_updates, new_commit, commits = tracker.check_for_updates()
        
        self.assertTrue(has_updates)
        self.assertEqual(new_commit, "new123456789")
        self.assertEqual(len(commits), 2)
        self.assertIn("New feature added", commits[0])
        self.assertIn("Bug fix", commits[1])
    
    @patch('git.Repo')
    @patch('pathlib.Path.exists')
    def test_get_file_changes(self, mock_exists, mock_repo_class):
        """Test getting file changes between commits."""
        # Mock diff items
        mock_diff_item1 = Mock()
        mock_diff_item1.change_type = 'M'
        mock_diff_item1.a_path = 'src/DTO/User.php'
        mock_diff_item1.b_path = 'src/DTO/User.php'
        
        mock_diff_item2 = Mock()
        mock_diff_item2.change_type = 'A'
        mock_diff_item2.a_path = None
        mock_diff_item2.b_path = 'src/DTO/NewClass.php'
        
        mock_diff_item3 = Mock()
        mock_diff_item3.change_type = 'D'
        mock_diff_item3.a_path = 'src/DTO/OldClass.php'
        mock_diff_item3.b_path = None
        
        # Mock commits and diff
        mock_commit1 = Mock()
        mock_commit2 = Mock()
        mock_diff = [mock_diff_item1, mock_diff_item2, mock_diff_item3]
        mock_commit1.diff.return_value = mock_diff
        
        mock_repo = Mock()
        mock_repo.commit.side_effect = [mock_commit1, mock_commit2]
        mock_repo_class.return_value = mock_repo
        mock_exists.return_value = True
        
        tracker = UpstreamTracker(str(self.config_path))
        changes = tracker.get_file_changes("abc123", "def456")
        
        self.assertIn('src/DTO/User.php', changes['modified'])
        self.assertIn('src/DTO/NewClass.php', changes['added'])
        self.assertIn('src/DTO/OldClass.php', changes['deleted'])
    
    @patch('git.Repo')
    @patch('pathlib.Path.exists')
    def test_generate_diff_report(self, mock_exists, mock_repo_class):
        """Test generating diff report."""
        # Mock diff item
        mock_diff_item = Mock()
        mock_diff_item.diff = b"@@ -1,3 +1,3 @@\n-old line\n+new line\n"
        
        # Mock commits and diff
        mock_commit1 = Mock()
        mock_commit2 = Mock()
        mock_diff = [mock_diff_item]
        mock_commit1.diff.return_value = mock_diff
        
        mock_repo = Mock()
        mock_repo.commit.side_effect = [mock_commit1, mock_commit2]
        mock_repo_class.return_value = mock_repo
        mock_exists.return_value = True
        
        tracker = UpstreamTracker(str(self.config_path))
        
        with patch.object(tracker, 'get_file_changes', return_value={'modified': ['test.php'], 'added': [], 'deleted': [], 'renamed': []}):
            report = tracker.generate_diff_report("abc123", "def456")
            
            self.assertIn("# Upstream Diff Report", report)
            self.assertIn("From commit: abc123", report)
            self.assertIn("To commit: def456", report)
            self.assertIn("Total files changed: 1", report)
            self.assertIn("Modified Files (1)", report)
            self.assertIn("- test.php", report)
    
    def test_update_tracking(self):
        """Test updating tracking information."""
        tracker = UpstreamTracker(str(self.config_path))
        
        new_commit = "new123456789"
        tracker.update_tracking(new_commit)
        
        # Verify the config was updated
        self.assertEqual(tracker.config["tracking"]["current_commit"], new_commit)
        self.assertIsNotNone(tracker.config["tracking"]["last_checked"])
        
        # Verify it was saved to file
        with open(self.config_path, 'r') as f:
            saved_config = json.load(f)
        
        self.assertEqual(saved_config["tracking"]["current_commit"], new_commit)
    
    def test_status_output(self):
        """Test status output method."""
        tracker = UpstreamTracker(str(self.config_path))
        
        # Capture stdout
        from io import StringIO
        import sys
        
        captured_output = StringIO()
        sys.stdout = captured_output
        
        try:
            tracker.status()
            output = captured_output.getvalue()
            
            self.assertIn("=== Upstream Tracking Status ===", output)
            self.assertIn("Repository: defstudio/telegraph", output)
            self.assertIn("Current commit: abc12345", output)
            self.assertIn("Files extracted: 118", output)
            self.assertIn("Up to date: True", output)
        finally:
            sys.stdout = sys.__stdout__


class TestUpstreamTrackerIntegration(unittest.TestCase):
    """Integration tests that require actual git operations."""
    
    def setUp(self):
        """Set up test fixtures."""
        self.test_dir = tempfile.mkdtemp()
        self.config_path = Path(self.test_dir) / "test_upstream.json"
        
        # Create a minimal config for integration tests
        self.config = {
            "repository": {
                "name": "test/repo",
                "url": "https://github.com/test/repo",
                "local_path": str(Path(self.test_dir) / "test_repo")
            },
            "tracking": {
                "current_commit": "abc123456789",
                "last_checked": None,
                "last_sync_commit": "abc123456789",
                "created": "2025-11-07"
            },
            "files": {"extracted_count": 10},
            "sync_status": {"up_to_date": True, "pending_changes": [], "conflicts": []}
        }
        
        with open(self.config_path, 'w') as f:
            json.dump(self.config, f, indent=2)
    
    def tearDown(self):
        """Clean up test files."""
        import shutil
        shutil.rmtree(self.test_dir, ignore_errors=True)
    
    def test_error_handling_missing_repo(self):
        """Test error handling when repository doesn't exist."""
        tracker = UpstreamTracker(str(self.config_path))
        
        # Try to check for updates with non-existent repo
        has_updates, new_commit, commits = tracker.check_for_updates()
        
        # Should handle the error gracefully
        self.assertFalse(has_updates)
        self.assertIsNone(new_commit)
        self.assertEqual(commits, [])
    
    def test_json_operations(self):
        """Test JSON file reading and writing operations."""
        tracker = UpstreamTracker(str(self.config_path))
        
        # Modify and save config
        original_commit = tracker.config["tracking"]["current_commit"]
        new_commit = "def987654321"
        
        tracker.config["tracking"]["current_commit"] = new_commit
        tracker._save_config()
        
        # Create new tracker instance to test loading
        tracker2 = UpstreamTracker(str(self.config_path))
        
        self.assertEqual(tracker2.config["tracking"]["current_commit"], new_commit)
        self.assertNotEqual(tracker2.config["tracking"]["current_commit"], original_commit)


if __name__ == '__main__':
    # Run the tests
    unittest.main(verbosity=2)