# Security Considerations

## Input Validation
- All input is trimmed and validated
- SQL queries use parameterized statements (PDO prepared statements)
- Protection against SQL injection

## Database
- Uses SQLite with file-based storage
- Consider moving to PostgreSQL for production
- Add authentication for production use

## Future Improvements
- Add API key authentication
- Implement rate limiting
- Add HTTPS support
- Database encryption
