<?php
namespace App\Core;

class App
{
    private static array $dependencies = [];

    /**
     * Charge les dépendances depuis le fichier services.yml
     */
    private static function loadDependencies(): void
    {
        if (!empty(self::$dependencies)) {
            return;
        }

        $configPath = __DIR__ . '/../config/services.yml';
        
        if (!file_exists($configPath)) {
            throw new \Exception("Le fichier de configuration '$configPath' n'existe pas.");
        }

        $content = file_get_contents($configPath);
        $lines = explode("\n", $content);
        $currentSection = null;

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Ignorer les lignes vides et commentaires
            if (empty($line) || $line[0] === '#') {
                continue;
            }

            // Section (se termine par : et pas d'espaces)
            if (substr($line, -1) === ':' && strpos($line, ' ') === false && strpos($line, '-') === false) {
                $currentSection = str_replace(':', '', $line);
                self::$dependencies[$currentSection] = [];
                continue;
            }

            // Élément de liste (commence par  - )
            if (substr($line, 0, 2) === '- ' || substr($line, 0, 4) === '  - ') {
                // Nettoyer la ligne pour extraire la classe
                $value = str_replace(['- ', '  - '], '', $line);
                $value = trim($value);
                
                if ($currentSection && !empty($value)) {
                    self::$dependencies[$currentSection][] = $value;
                }
            }
        }
    }

    public static function getDependency(string $className)
    {
        self::loadDependencies();

        foreach (self::$dependencies as $category => $classes) {
            foreach ($classes as $fullClassName) {
                $shortName = substr($fullClassName, strrpos($fullClassName, '\\') + 1);

                if ($shortName === $className) {
                    if (!class_exists($fullClassName)) {
                        throw new \Exception("La classe '$fullClassName' n'existe pas.");
                    }

                    if (method_exists($fullClassName, 'getInstance')) {
                        return $fullClassName::getInstance();
                    }

                    return new $fullClassName();
                }
            }
        }

        throw new \Exception("La classe '$className' n'a pas été trouvée dans les dépendances.");
    }
}
