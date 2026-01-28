<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.moko-cassiopeia
 *
 * @copyright   (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * SPDX-License-Identifier: GPL-3.0-or-later
 * 
 * FILE INFORMATION
 * DEFGROUP: Joomla.Template.Site
 * INGROUP: Moko-Cassiopeia
 * PATH: ./templates/moko-cassiopeia/script.php
 * VERSION: 03.08.00
 * BRIEF: Installation and update script for Moko-Cassiopeia template
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;

/**
 * Installation script for Moko-Cassiopeia template
 *
 * @since  03.08.00
 */
return new class () implements InstallerScriptInterface {
    /**
     * The template's minimum supported Joomla version
     *
     * @var    string
     * @since  03.08.00
     */
    private $minimumJoomla = '4.0';

    /**
     * The template's minimum supported PHP version
     *
     * @var    string
     * @since  03.08.00
     */
    private $minimumPhp = '7.4';

    /**
     * List of files to be removed during update (old/deprecated files)
     *
     * @var    array
     * @since  03.08.00
     */
    private $filesToRemove = [
        // Add deprecated files here as needed
        // Example: 'media/templates/site/moko-cassiopeia/js/old-script.js',
    ];

    /**
     * List of folders to be removed during update (old/deprecated folders)
     *
     * @var    array
     * @since  03.08.00
     */
    private $foldersToRemove = [
        // Add deprecated folders here as needed
        // Example: 'media/templates/site/moko-cassiopeia/old-assets',
    ];

    /**
     * Function called before extension installation/update/removal procedure commences
     *
     * @param   string            $type    The type of change (install or discover_install, update, uninstall)
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since   03.08.00
     */
    public function preflight(string $type, InstallerAdapter $parent): bool
    {
        // Check minimum Joomla version
        if (version_compare(JVERSION, $this->minimumJoomla, '<')) {
            Factory::getApplication()->enqueueMessage(
                sprintf(
                    Text::_('JLIB_INSTALLER_MINIMUM_JOOMLA'),
                    $this->minimumJoomla
                ),
                'error'
            );
            return false;
        }

        // Check minimum PHP version
        if (version_compare(PHP_VERSION, $this->minimumPhp, '<')) {
            Factory::getApplication()->enqueueMessage(
                sprintf(
                    Text::_('JLIB_INSTALLER_MINIMUM_PHP'),
                    $this->minimumPhp
                ),
                'error'
            );
            return false;
        }

        return true;
    }

    /**
     * Function called after extension installation/update/removal procedure completes
     *
     * @param   string            $type    The type of change (install or discover_install, update, uninstall)
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since   03.08.00
     */
    public function postflight(string $type, InstallerAdapter $parent): bool
    {
        // Only run cleanup on update
        if ($type === 'update') {
            $this->cleanupMediaFolder();
        }

        // Display success message
        if ($type === 'install') {
            Factory::getApplication()->enqueueMessage(
                'Moko-Cassiopeia template has been successfully installed!',
                'success'
            );
        } elseif ($type === 'update') {
            Factory::getApplication()->enqueueMessage(
                'Moko-Cassiopeia template has been successfully updated to version 03.08.00!',
                'success'
            );
        }

        return true;
    }

    /**
     * Function called when extension is installed
     *
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since   03.08.00
     */
    public function install(InstallerAdapter $parent): bool
    {
        return true;
    }

    /**
     * Function called when extension is updated
     *
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since   03.08.00
     */
    public function update(InstallerAdapter $parent): bool
    {
        return true;
    }

    /**
     * Function called when extension is uninstalled
     *
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since   03.08.00
     */
    public function uninstall(InstallerAdapter $parent): bool
    {
        return true;
    }

    /**
     * Clean up the media folder by removing old and deprecated files
     *
     * @return  void
     *
     * @since   03.08.00
     */
    private function cleanupMediaFolder(): void
    {
        $app = Factory::getApplication();
        $mediaPath = JPATH_ROOT . '/media/templates/site/moko-cassiopeia';
        $removedFiles = 0;
        $removedFolders = 0;
        $errors = [];

        // Set up logging
        Log::addLogger(
            ['text_file' => 'moko_cassiopeia_cleanup.php'],
            Log::ALL,
            ['moko-cassiopeia-cleanup']
        );

        Log::add('Starting media folder cleanup', Log::INFO, 'moko-cassiopeia-cleanup');

        // Remove deprecated files
        foreach ($this->filesToRemove as $file) {
            $filePath = JPATH_ROOT . '/' . $file;
            
            if (File::exists($filePath)) {
                try {
                    if (File::delete($filePath)) {
                        $removedFiles++;
                        Log::add("Removed file: {$file}", Log::INFO, 'moko-cassiopeia-cleanup');
                    } else {
                        $errors[] = "Failed to remove file: {$file}";
                        Log::add("Failed to remove file: {$file}", Log::WARNING, 'moko-cassiopeia-cleanup');
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error removing file {$file}: " . $e->getMessage();
                    Log::add("Error removing file {$file}: " . $e->getMessage(), Log::ERROR, 'moko-cassiopeia-cleanup');
                }
            }
        }

        // Remove deprecated folders
        foreach ($this->foldersToRemove as $folder) {
            $folderPath = JPATH_ROOT . '/' . $folder;
            
            if (Folder::exists($folderPath)) {
                try {
                    if (Folder::delete($folderPath)) {
                        $removedFolders++;
                        Log::add("Removed folder: {$folder}", Log::INFO, 'moko-cassiopeia-cleanup');
                    } else {
                        $errors[] = "Failed to remove folder: {$folder}";
                        Log::add("Failed to remove folder: {$folder}", Log::WARNING, 'moko-cassiopeia-cleanup');
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error removing folder {$folder}: " . $e->getMessage();
                    Log::add("Error removing folder {$folder}: " . $e->getMessage(), Log::ERROR, 'moko-cassiopeia-cleanup');
                }
            }
        }

        // Clean up empty directories in media folder
        if (is_dir($mediaPath)) {
            $this->removeEmptyDirectories($mediaPath);
        }

        // Display cleanup summary
        if ($removedFiles > 0 || $removedFolders > 0) {
            $message = sprintf(
                'Media folder cleanup: Removed %d file(s) and %d folder(s).',
                $removedFiles,
                $removedFolders
            );
            $app->enqueueMessage($message, 'info');
            Log::add($message, Log::INFO, 'moko-cassiopeia-cleanup');
        }

        // Display errors if any
        foreach ($errors as $error) {
            $app->enqueueMessage($error, 'warning');
        }

        Log::add('Media folder cleanup completed', Log::INFO, 'moko-cassiopeia-cleanup');
    }

    /**
     * Recursively remove empty directories
     *
     * @param   string  $path  The directory path to check
     *
     * @return  boolean  True if directory was removed, false otherwise
     *
     * @since   03.08.00
     */
    private function removeEmptyDirectories(string $path): bool
    {
        if (!is_dir($path)) {
            return false;
        }

        $empty = true;
        $items = glob($path . '/{,.}*', GLOB_BRACE);

        foreach ($items as $item) {
            $basename = basename($item);
            
            // Skip . and ..
            if ($basename === '.' || $basename === '..') {
                continue;
            }

            if (is_dir($item)) {
                if (!$this->removeEmptyDirectories($item)) {
                    $empty = false;
                }
            } else {
                $empty = false;
            }
        }

        // Remove directory if it's empty
        if ($empty && $path !== JPATH_ROOT . '/media/templates/site/moko-cassiopeia') {
            try {
                if (Folder::delete($path)) {
                    Log::add("Removed empty directory: {$path}", Log::INFO, 'moko-cassiopeia-cleanup');
                    return true;
                }
            } catch (\Exception $e) {
                Log::add("Error removing empty directory {$path}: " . $e->getMessage(), Log::WARNING, 'moko-cassiopeia-cleanup');
            }
        }

        return $empty;
    }
};
