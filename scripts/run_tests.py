#!/usr/bin/env python3
"""
Test runner for upstream tracking scripts.

Usage:
    python scripts/run_tests.py
    python scripts/run_tests.py -v  # verbose output
"""

import sys
import unittest
import os

# Add the scripts directory to the path
sys.path.insert(0, os.path.dirname(__file__))

def run_tests(verbosity=1):
    """Run all tests for the upstream tracking functionality."""
    # Discover and run tests
    loader = unittest.TestLoader()
    start_dir = os.path.dirname(__file__)
    suite = loader.discover(start_dir, pattern='test_*.py')
    
    runner = unittest.TextTestRunner(verbosity=verbosity)
    result = runner.run(suite)
    
    # Return exit code based on test results
    return 0 if result.wasSuccessful() else 1

if __name__ == '__main__':
    # Check for verbose flag
    verbosity = 2 if '-v' in sys.argv or '--verbose' in sys.argv else 1
    
    print("Running upstream tracking tests...")
    exit_code = run_tests(verbosity)
    
    if exit_code == 0:
        print("\n[PASS] All tests passed!")
    else:
        print("\n[FAIL] Some tests failed!")
    
    sys.exit(exit_code)