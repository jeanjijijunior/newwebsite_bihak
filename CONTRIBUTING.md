# Contributing to Bihak Center Website

Thank you for your interest in contributing to the Bihak Center website! This document provides guidelines for contributing to the project.

## Code of Conduct

By participating in this project, you agree to maintain a respectful and inclusive environment for all contributors.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue with:
- Clear description of the problem
- Steps to reproduce
- Expected vs actual behavior
- Screenshots (if applicable)
- Browser and OS information

### Suggesting Enhancements

For feature requests:
- Clearly describe the proposed feature
- Explain why it would be useful
- Provide examples if possible

### Pull Requests

1. Fork the repository
2. Create a new branch from `main`
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Make your changes
4. Test thoroughly
5. Commit with clear messages
   ```bash
   git commit -m "Add: brief description of changes"
   ```
6. Push to your fork
   ```bash
   git push origin feature/your-feature-name
   ```
7. Create a Pull Request

## Development Guidelines

### Code Style

**PHP:**
- Use PSR-12 coding standard
- Document functions with PHPDoc comments
- Sanitize all user inputs
- Use prepared statements for SQL queries

**HTML:**
- Use semantic HTML5 elements
- Include proper accessibility attributes
- Validate markup

**CSS:**
- Use meaningful class names
- Follow BEM methodology where applicable
- Keep selectors simple
- Add comments for complex styles

**JavaScript:**
- Use ES6+ features
- Add JSDoc comments for functions
- Handle errors gracefully
- Use meaningful variable names

### Commit Messages

Format: `Type: Description`

Types:
- `Add:` New feature or file
- `Fix:` Bug fix
- `Update:` Modify existing feature
- `Refactor:` Code restructuring
- `Docs:` Documentation changes
- `Style:` Formatting, no code change
- `Test:` Adding tests

Example:
```
Add: User registration form
Fix: Navigation menu alignment on mobile
Update: Database connection error handling
```

### Testing

- Test on multiple browsers
- Verify responsive design
- Check database operations
- Validate form submissions
- Test language switching

## Project Structure

Follow the established structure:
```
assets/      - CSS, JS, images
config/      - Configuration files
includes/    - Database schemas
public/      - Public web files
```

## Questions?

Feel free to open an issue for any questions or clarifications.

Thank you for contributing to Bihak Center!
