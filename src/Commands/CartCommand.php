<?php
declare(strict_types=1);

namespace Eddieodira\Shoppingcart\Commands;

use Config\Autoload;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;

class CartCommand extends BaseCommand
{
    /**
     * The group the command is lumped under
     * when listing commands.
     *
     * @var string
     */
    protected $group = 'ShoppingCart';

    /**
     * The command's name.
     *
     * @var string
     */
    protected $name = 'cart:publish';

    /**
     * The command's short description.
     *
     * @var string
     */
    protected $description = 'Publish config cart to folder App\Config';

    /**
     * The command's usage.
     *
     * @var string
     */
    protected $usage = 'cart:publish';

    /**
     * The path directory.
     *
     * @var string
     */
    protected $sourcePath;


    /**
     * Displays the help for the spark cli script itself.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $this->determineSourcePath();
        $this->publishConfig();
    }


    protected function publishConfig()
    {
        $path = "{$this->sourcePath}/Config/Cart.php";

        $content = file_get_contents($path);
        $content = str_replace('namespace Eddieodira\Shoppingcart\Config', 'namespace App\Config', $content);

        $this->writeFile('Config/Cart.php', $content);
    }


    //--------------------------------------------------------------------
    // Utilities
    //--------------------------------------------------------------------

    /**
     * Replaces the Myth\Auth namespace in the published
     * file with the applications current namespace.
     *
     * @param string $contents
     * @param string $originalNamespace
     * @param string $newNamespace
     *
     * @return string
     */
    protected function replaceNamespace(string $contents, string $originalNamespace, string $newNamespace): string
    {
        $appNamespace = APP_NAMESPACE;
        $originalNamespace = "namespace {$originalNamespace}";
        $newNamespace = "namespace {$appNamespace}\\{$newNamespace}";

        return str_replace($originalNamespace, $newNamespace, $contents);
    }

    /**
     * Determines the current source path from which all other files are located.
     */
    protected function determineSourcePath()
    {
        $this->sourcePath = realpath(__DIR__.'/../');

        if ($this->sourcePath == '/' || empty($this->sourcePath)) {
            CLI::error('Unable to determine the correct source directory. Bailing.');
            exit();
        }
    }

    /**
     * Write a file, catching any exceptions and showing a
     * nicely formatted error.
     *
     * @param string $path
     * @param string $content
     */
    protected function writeFile(string $path, string $content)
    {
        $config = new Autoload();
        $filePath = $config->psr4[APP_NAMESPACE] . $path;
        $directory = dirname($filePath);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        try {
            write_file($directory, $content);
        } catch (\Exception $e) {
            $this->showError($e);
            exit();
        }

        $path = str_replace($config->psr4[APP_NAMESPACE], '', $path);

        CLI::write(CLI::color('  created: ', 'green') . $path);
    }
}