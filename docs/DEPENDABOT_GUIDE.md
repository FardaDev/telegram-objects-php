# Dependabot Guide

## What is Dependabot?

**Dependabot** is GitHub's automated dependency management bot. It helps keep your project secure and up-to-date by automatically checking for dependency updates and creating pull requests.

## How It Works

1. **Monitors Dependencies**: Checks your `composer.json`, GitHub Actions workflows, and Python requirements
2. **Checks for Updates**: Runs on a schedule (we configured it for weekly on Mondays)
3. **Creates PRs**: Automatically opens pull requests when updates are available
4. **Runs CI Tests**: Each PR triggers your full CI pipeline to ensure updates don't break anything
5. **Can Auto-Merge**: (Optional) Can automatically merge PRs if tests pass

## Our Configuration

Located in `.github/dependabot.yml`:

### Composer Dependencies
- **Checks**: PHP packages in `composer.json`
- **Schedule**: Weekly on Mondays
- **Max PRs**: 5 at a time
- **Labels**: `dependencies`, `composer`

### GitHub Actions
- **Checks**: Workflow action versions (e.g., `actions/checkout@v4`)
- **Schedule**: Weekly on Mondays
- **Max PRs**: 5 at a time
- **Labels**: `dependencies`, `github-actions`

### Python Dependencies
- **Checks**: Python packages in `scripts/requirements.txt`
- **Schedule**: Weekly on Mondays
- **Max PRs**: 3 at a time
- **Labels**: `dependencies`, `python`

## Common Dependabot Commands

You can control Dependabot by commenting on its PRs:

- `@dependabot rebase` - Rebase the PR against the latest main branch
- `@dependabot recreate` - Close and recreate the PR from scratch
- `@dependabot merge` - Merge the PR (if tests pass)
- `@dependabot squash and merge` - Squash commits and merge
- `@dependabot cancel merge` - Cancel a pending merge
- `@dependabot reopen` - Reopen a closed PR
- `@dependabot close` - Close the PR without merging
- `@dependabot ignore this dependency` - Never update this dependency
- `@dependabot ignore this major version` - Ignore major version updates
- `@dependabot ignore this minor version` - Ignore minor version updates

## Current Situation (November 2025)

### The Problem
6 Dependabot PRs were created before we fixed two CI issues:
1. Pest test configuration (fixed by adding explicit `tests` directory)
2. Line ending enforcement (fixed by adding `.gitattributes`)

### The Solution
We asked Dependabot to rebase all 6 PRs using `@dependabot rebase`. This will:
- Update each PR branch with the latest main branch
- Pick up our CI fixes (`.gitattributes` and Pest configuration)
- Re-run all tests with the fixes in place
- Tests should now pass ✅

### What to Expect
After rebasing, each PR will:
1. Show "Dependabot is rebasing this PR" message
2. Force-push the updated branch
3. Trigger CI workflows automatically
4. Show green checkmarks if everything passes

## Managing Dependabot PRs

### When Tests Pass ✅
1. **Review the changes**: Check what's being updated
2. **Check the changelog**: Look for breaking changes
3. **Merge the PR**: Use "Squash and merge" for clean history

### When Tests Fail ❌
1. **Check the failure**: Is it a real issue or a flaky test?
2. **If it's a breaking change**: 
   - Close the PR with a comment explaining why
   - Or fix your code to work with the new version
3. **If it's a flaky test**: 
   - Comment `@dependabot rebase` to retry
4. **If it's a Dependabot bug**:
   - Comment `@dependabot recreate` to start fresh

### Bulk Actions
If you have many PRs and want to merge them all:
```bash
# List all Dependabot PRs
gh pr list --label dependencies

# Merge all passing PRs (be careful!)
gh pr list --label dependencies --json number --jq '.[].number' | \
  xargs -I {} gh pr merge {} --squash --auto
```

## Security Updates

Dependabot also creates PRs for **security vulnerabilities**:
- These are marked with a security label
- They should be reviewed and merged ASAP
- GitHub will show a security alert on your repo

## Best Practices

1. **Review regularly**: Check Dependabot PRs weekly
2. **Don't ignore security updates**: Merge them quickly
3. **Test locally if unsure**: Pull the PR branch and test manually
4. **Keep CI green**: Fix any CI issues immediately so Dependabot PRs can pass
5. **Use semantic versioning**: Follow semver so Dependabot can make smart decisions
6. **Configure auto-merge carefully**: Only for minor/patch updates, not major versions

## Troubleshooting

### "Dependabot couldn't find a composer.json"
- Check that `composer.json` is in the repository root
- Verify the `directory` setting in `.github/dependabot.yml`

### "Dependabot couldn't update dependencies"
- Check if there are conflicting version constraints
- Look at the PR description for error details
- Try running `composer update` locally to see the issue

### "Tests keep failing on Dependabot PRs"
- Fix the CI issue in main first
- Then rebase all Dependabot PRs with `@dependabot rebase`
- This is exactly what we just did!

### "Too many PRs open"
- Increase `open-pull-requests-limit` in config
- Or close/merge some PRs to make room
- Or temporarily disable Dependabot for that ecosystem

## Disabling Dependabot

To temporarily disable Dependabot:
1. Go to repository Settings → Code security and analysis
2. Find "Dependabot version updates"
3. Click "Disable"

Or remove/comment out the ecosystem in `.github/dependabot.yml`.

## Further Reading

- [Dependabot Documentation](https://docs.github.com/en/code-security/dependabot)
- [Configuration Options](https://docs.github.com/en/code-security/dependabot/dependabot-version-updates/configuration-options-for-the-dependabot.yml-file)
- [Dependabot Commands](https://docs.github.com/en/code-security/dependabot/working-with-dependabot/managing-pull-requests-for-dependency-updates)
