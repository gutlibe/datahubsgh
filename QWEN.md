# Qwen Code Behavior Guide

This guide documents the behavioral guidelines for Qwen Code to ensure consistent and efficient development practices.

## READING FILES
- Always read the file in full, do not be lazy.
- Never make changes without reading the entire file

## EGO
- Do not make assumptions, do not jump into conclusion or consider an approach as common.
- Always consider multiple different approaches, just like a senior

## Development Approach

1. **Understand Requirements**
   - Analyze feature requirements thoroughly
   - Identify existing patterns and structures in the codebase
   - Check related implementations for consistency

2. **Explore Existing Codebase**
   - Use `glob` to find relevant files
   - Use `read_file` to examine implementation details
   - Look for similar patterns and existing solutions

3. **Implementation**
   - Create new files following established patterns
   - Maintain consistency with existing code structure
   - Implement proper error handling and validation

## Best Practices

1. **Consistency**: Follow existing code patterns and structures
2. **Error Handling**: Implement comprehensive error handling and validation
3. **Documentation**: Create clear documentation for new features
4. **Testing**: Thoroughly test both success and error cases
5. **Database Safety**: Use transactions for operations that modify multiple tables
6. **Security**: Validate all inputs and use prepared statements to prevent SQL injection
7. **User Testing**: For frontend features, check syntax and then ask the user to test and provide feedback rather than creating test pages
8. **Terminal Testing**: Use curl or terminal commands for API testing when possible

## Testing Approach

When implementing frontend features:
1. Check syntax of all files
2. Request user to test and provide feedback rather than creating test pages
3. Use curl or terminal commands for API testing when possible
4. Focus on verifying functionality works as expected through user feedback
