<?php

declare(strict_types=1);

namespace Fluxa\CLI\Commands;

use Fluxa\CLI\CommandInterface;
use Fluxa\CLI\Console;

final class InstallCommand implements CommandInterface
{
    public function name(): string
    {
        return 'install';
    }

    public function description(): string
    {
        return 'Initialize a new Fluxa project structure';
    }

    public function execute(array $args): int
    {
        $base = getcwd();

        $dirs = ['config', 'public', 'src/Controllers', 'src/Middleware'];

        foreach ($dirs as $dir) {
            $path = $base . '/' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
                Console::success("Created {$dir}/");
            }
        }

        $this->writeIfMissing($base . '/public/index.php', $this->indexTemplate());
        $this->writeIfMissing($base . '/config/app.php', $this->configTemplate());
        $this->writeIfMissing($base . '/.gitignore', "/vendor/\n.env\n");

        Console::line('');
        Console::success('Fluxa project initialized!');
        Console::info('Run "fluxa serve" to start the development server.');
        Console::line('');

        return 0;
    }

    private function writeIfMissing(string $path, string $content): void
    {
        if (file_exists($path)) {
            return;
        }
        file_put_contents($path, $content);
        $relative = str_replace(getcwd() . '/', '', $path);
        Console::success("Created {$relative}");
    }

    private function indexTemplate(): string
    {
        return <<<'PHP'
        <?php

        declare(strict_types=1);

        require __DIR__ . '/../vendor/autoload.php';

        use Fluxa\Core\Application;
        use Fluxa\Http\Request;
        use Fluxa\Http\Response;

        $app = new Application(dirname(__DIR__));

        $app->router->get('/', fn(Request $request) => Response::json([
            'message' => 'Welcome to Fluxa!',
        ]));

        $app->handle();

        PHP;
    }

    private function configTemplate(): string
    {
        return <<<'PHP'
        <?php

        declare(strict_types=1);

        return [
            'name' => 'My Fluxa App',
            'debug' => true,
        ];

        PHP;
    }
}
