#!/usr/bin/env python3
"""
Upstream synchronization script for telegram-objects-php library.

This script tracks changes in the DefStudio/Telegraph repository and helps
maintain synchronization with the upstream source.
"""

import json
import os
import sys
from datetime import datetime
from pathlib import Path
from typing import Dict, List, Optional, Tuple

try:
    import git
except ImportError:
    print("Error: GitPython is required. Install with: pip install gitpython")
    sys.exit(1)


class UpstreamTracker:
    """Tracks and manages upstream repository synchronization."""
    
    def __init__(self, config_path: str = "upstream.json"):
        self.config_path = Path(config_path)
        self.config = self._load_config()
        self.repo_path = Path(self.config["repository"]["local_path"])
        
    def _load_config(self) -> Dict:
        """Load the upstream configuration file."""
        if not self.config_path.exists():
            raise FileNotFoundError(f"Configuration file {self.config_path} not found")
        
        with open(self.config_path, 'r') as f:
            return json.load(f)
    
    def _save_config(self) -> None:
        """Save the updated configuration file."""
        with open(self.config_path, 'w') as f:
            json.dump(self.config, f, indent=2)
    
    def _get_repo(self) -> git.Repo:
        """Get the git repository object."""
        if not self.repo_path.exists():
            raise FileNotFoundError(f"Repository path {self.repo_path} not found")
        
        try:
            return git.Repo(self.repo_path)
        except git.InvalidGitRepositoryError:
            raise ValueError(f"Invalid git repository at {self.repo_path}")
    
    def get_current_commit(self) -> str:
        """Get the current commit hash of the upstream repository."""
        repo = self._get_repo()
        return repo.head.commit.hexsha
    
    def check_for_updates(self) -> Tuple[bool, Optional[str], List[str]]:
        """
        Check if there are updates available from upstream.
        
        Returns:
            Tuple of (has_updates, new_commit_hash, list_of_new_commits)
        """
        try:
            repo = self._get_repo()
            
            # Fetch latest changes
            print("Fetching latest changes from upstream...")
            repo.remotes.origin.fetch()
            
            current_commit = self.config["tracking"]["current_commit"]
            latest_commit = repo.head.commit.hexsha
            
            if current_commit == latest_commit:
                return False, None, []
            
            # Get list of commits between current and latest
            commits = list(repo.iter_commits(f"{current_commit}..{latest_commit}"))
            commit_messages = [f"{c.hexsha[:8]} - {c.message.strip()}" for c in commits]
            
            return True, latest_commit, commit_messages
            
        except Exception as e:
            print(f"Error checking for updates: {e}")
            return False, None, []
    
    def get_file_changes(self, from_commit: str, to_commit: str) -> Dict[str, List[str]]:
        """
        Get the list of files changed between two commits.
        
        Returns:
            Dictionary with categories of changed files
        """
        repo = self._get_repo()
        
        try:
            # Get the diff between commits
            diff = repo.commit(from_commit).diff(repo.commit(to_commit))
            
            changes = {
                "modified": [],
                "added": [],
                "deleted": [],
                "renamed": []
            }
            
            for item in diff:
                file_path = item.a_path or item.b_path
                
                if item.change_type == 'M':
                    changes["modified"].append(file_path)
                elif item.change_type == 'A':
                    changes["added"].append(file_path)
                elif item.change_type == 'D':
                    changes["deleted"].append(file_path)
                elif item.change_type == 'R':
                    changes["renamed"].append(f"{item.a_path} -> {item.b_path}")
            
            return changes
            
        except Exception as e:
            print(f"Error getting file changes: {e}")
            return {"modified": [], "added": [], "deleted": [], "renamed": []}
    
    def generate_diff_report(self, from_commit: str, to_commit: str, output_file: str = None) -> str:
        """
        Generate a detailed diff report between two commits.
        
        Args:
            from_commit: Starting commit hash
            to_commit: Ending commit hash
            output_file: Optional file to save the report
            
        Returns:
            The diff report as a string
        """
        repo = self._get_repo()
        
        try:
            # Get the diff
            diff = repo.commit(from_commit).diff(repo.commit(to_commit), create_patch=True)
            
            report_lines = [
                f"# Upstream Diff Report",
                f"Generated: {datetime.now().isoformat()}",
                f"From commit: {from_commit}",
                f"To commit: {to_commit}",
                f"Repository: {self.config['repository']['name']}",
                "",
                "## Summary",
                f"Total files changed: {len(diff)}",
                ""
            ]
            
            # Add file changes summary
            changes = self.get_file_changes(from_commit, to_commit)
            for change_type, files in changes.items():
                if files:
                    report_lines.append(f"### {change_type.title()} Files ({len(files)})")
                    for file in files:
                        report_lines.append(f"- {file}")
                    report_lines.append("")
            
            # Add detailed diff
            report_lines.append("## Detailed Diff")
            report_lines.append("```diff")
            
            for item in diff:
                if item.diff:
                    report_lines.append(item.diff.decode('utf-8', errors='ignore'))
            
            report_lines.append("```")
            
            report = "\n".join(report_lines)
            
            # Save to file if requested
            if output_file:
                with open(output_file, 'w') as f:
                    f.write(report)
                print(f"Diff report saved to {output_file}")
            
            return report
            
        except Exception as e:
            error_msg = f"Error generating diff report: {e}"
            print(error_msg)
            return error_msg
    
    def update_tracking(self, new_commit: str) -> None:
        """Update the tracking information with a new commit."""
        self.config["tracking"]["current_commit"] = new_commit
        self.config["tracking"]["last_checked"] = datetime.now().isoformat()
        self._save_config()
        print(f"Updated tracking to commit: {new_commit[:8]}")
    
    def status(self) -> None:
        """Display current status information."""
        print("=== Upstream Tracking Status ===")
        print(f"Repository: {self.config['repository']['name']}")
        print(f"Local path: {self.config['repository']['local_path']}")
        print(f"Current commit: {self.config['tracking']['current_commit'][:8]}")
        print(f"Last checked: {self.config['tracking']['last_checked'] or 'Never'}")
        print(f"Files extracted: {self.config['files']['extracted_count']}")
        print(f"Up to date: {self.config['sync_status']['up_to_date']}")
        
        if self.config['sync_status']['pending_changes']:
            print(f"Pending changes: {len(self.config['sync_status']['pending_changes'])}")


def main():
    """Main entry point for the script."""
    import argparse
    
    parser = argparse.ArgumentParser(description="Upstream synchronization tool")
    parser.add_argument("--config", default="upstream.json", help="Configuration file path")
    parser.add_argument("--check", action="store_true", help="Check for upstream updates")
    parser.add_argument("--status", action="store_true", help="Show current status")
    parser.add_argument("--diff", nargs=2, metavar=("FROM", "TO"), help="Generate diff between commits")
    parser.add_argument("--update", metavar="COMMIT", help="Update tracking to specific commit")
    parser.add_argument("--output", help="Output file for diff report")
    
    args = parser.parse_args()
    
    try:
        tracker = UpstreamTracker(args.config)
        
        if args.status:
            tracker.status()
        
        elif args.check:
            print("Checking for upstream updates...")
            has_updates, new_commit, commits = tracker.check_for_updates()
            
            if has_updates:
                print(f"✓ Updates available! New commit: {new_commit[:8]}")
                print(f"New commits ({len(commits)}):")
                for commit in commits:
                    print(f"  {commit}")
                
                # Generate diff report
                current_commit = tracker.config["tracking"]["current_commit"]
                print(f"\nGenerating diff report from {current_commit[:8]} to {new_commit[:8]}...")
                
                output_file = args.output or f"diff-{current_commit[:8]}-to-{new_commit[:8]}.md"
                tracker.generate_diff_report(current_commit, new_commit, output_file)
                
            else:
                print("✓ Repository is up to date")
        
        elif args.diff:
            from_commit, to_commit = args.diff
            print(f"Generating diff from {from_commit} to {to_commit}...")
            
            output_file = args.output or f"diff-{from_commit[:8]}-to-{to_commit[:8]}.md"
            tracker.generate_diff_report(from_commit, to_commit, output_file)
        
        elif args.update:
            tracker.update_tracking(args.update)
        
        else:
            parser.print_help()
    
    except Exception as e:
        print(f"Error: {e}")
        sys.exit(1)


if __name__ == "__main__":
    main()