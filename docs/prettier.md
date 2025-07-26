# Prettier Code Formatting

This document explains how Prettier is set up in the Campmaster project and how to use it for code formatting.

## Configuration

Prettier is configured in the project with the following settings (defined in `.prettierrc`):

- **Tab Width**: 4 spaces (2 spaces for YAML files)
- **Print Width**: 150 characters
- **Quotes**: Single quotes
- **Semicolons**: Required
- **Plugins**:
    - `prettier-plugin-organize-imports`: Automatically organizes imports
    - `prettier-plugin-tailwindcss`: Formats Tailwind CSS classes

## Ignored Files

The following files and directories are excluded from formatting (defined in `.prettierignore`):

- `resources/js/components/ui/*`: UI components from libraries
- `resources/js/ziggy.js`: Generated file for route handling
- `resources/views/mail/*`: Email templates

## Usage

### Format Code

To format all JavaScript and TypeScript files in the project:

```bash
npm run format
```

This command runs Prettier with the `--write` flag, which automatically fixes formatting issues.

### Check Formatting

To check if files need formatting without making changes:

```bash
npm run format:check
```

This command runs Prettier with the `--check` flag, which reports files that need formatting but doesn't modify them.

### Format Specific Files

To format specific files or directories:

```bash
npm run format -- path/to/file.js
npm run format -- path/to/directory
```

### Pre-commit Hook (Recommended)

It's recommended to set up a pre-commit hook to automatically format code before committing. This can be done using
Husky and lint-staged.

## Integration with ESLint

The project uses ESLint with `eslint-config-prettier` to avoid conflicts between ESLint and Prettier rules. This ensures
that ESLint doesn't report issues that Prettier will fix.

To run ESLint with automatic fixes:

```bash
npm run lint
```

## VS Code Integration

For VS Code users, it's recommended to install the Prettier extension and enable "Format on Save" for a seamless
experience.

Add the following to your VS Code settings:

```json
{
    "editor.defaultFormatter": "esbenp.prettier-vscode",
    "editor.formatOnSave": true
}
```
