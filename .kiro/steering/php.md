---
inclusion: always
---

# PHP Development Standards

## Code Structure
- **Always** start PHP files with `<?php declare(strict_types=1);`
- Use PSR-4 autoloading with proper namespace declarations
- Follow PSR-12 code style (4-space indentation, 120-character line limit)

## Type Safety
- Use strict typing for all parameters, return types, and properties
- Prefer `readonly` properties for immutable data
- Use union types (`string|null`) over mixed types when possible

## Object Design
- Avoid magic methods (`__get`, `__set`, `__call`) unless absolutely necessary
- Design immutable objects where possible - constructors should fully initialize state
- Use explicit getter/setter methods instead of property access

## Code Quality
- Use descriptive names for classes, methods, and variables (avoid abbreviations)
- Break methods longer than 20 lines into smaller, focused functions
- Prefer composition over inheritance
- Use early returns to reduce nesting levels

## Error Handling
- Use typed exceptions instead of generic `Exception`
- Validate input parameters and throw appropriate exceptions
- Use null coalescing (`??`) and null-safe operators (`?->`) appropriately
