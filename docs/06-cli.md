# CLI

## Commands

| Command | Description |
|---------|-------------|
| `fluxa install` | Scaffold a new Fluxa project |
| `fluxa serve` | Start the PHP development server |
| `fluxa make:controller <Name>` | Generate a controller class |
| `fluxa make:middleware <Name>` | Generate a middleware class |
| `fluxa config:get <key>` | Read a configuration value |
| `fluxa config:set <key> <value>` | Set a configuration value |
| `fluxa help` | Show available commands |
| `fluxa --version` | Show Fluxa version |

## Examples

```bash
# Initialize project
vendor/bin/fluxa install

# Start server on custom port
vendor/bin/fluxa serve --port 3000 --host 0.0.0.0

# Generate files
vendor/bin/fluxa make:controller User
vendor/bin/fluxa make:middleware RateLimit

# Configuration
vendor/bin/fluxa config:get app.name
vendor/bin/fluxa config:set app.debug true
```
