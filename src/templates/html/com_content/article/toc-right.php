<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.MokoCassiopeia
 *
 * @copyright   (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * FILE INFORMATION
 * DEFGROUP: Joomla.Template.Site
 * INGROUP: MokoCassiopeia
 * PATH: ./templates/mokocassiopeia/html/com_content/article/toc-right.php
 * VERSION: 03.06.02
 * BRIEF: Article layout with table of contents on the right side
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;

// Get article params
$params = $this->item->params;
$images = json_decode($this->item->images);
$urls = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$info = $params->get('info_block_position', 0);

// Check if associations are implemented. If they are, define the parameter.
$assocParam = (Associations::isEnabled() && $params->get('show_associations'));
?>

<div class="com-content-article item-page<?php echo $this->pageclass_sfx; ?>">
    <div class="row">
        <!-- Article Content -->
        <div class="col-lg-9 col-md-8 order-md-1">
            <meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? Factory::getApplication()->get('language') : $this->item->language; ?>" />

            <?php if ($this->params->get('show_page_heading')) : ?>
                <div class="page-header">
                    <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
                </div>
            <?php endif; ?>

            <?php if (!$this->print) : ?>
                <?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
                    <?php echo LayoutHelper::render('joomla.content.icons', ['params' => $params, 'item' => $this->item, 'print' => false]); ?>
                <?php endif; ?>
            <?php else : ?>
                <?php if ($params->get('show_print_icon')) : ?>
                    <?php echo LayoutHelper::render('joomla.content.icons', ['params' => $params, 'item' => $this->item, 'print' => true]); ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php echo $this->item->event->afterDisplayTitle; ?>

            <?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                <?php echo LayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
            <?php endif; ?>

            <?php echo $this->item->event->beforeDisplayContent; ?>

            <?php if (isset($urls) && ((!empty($urls->urls_position) && $urls->urls_position == '0') || ($params->get('urls_position') == '0' && empty($urls->urls_position))) || (empty($urls->urls_position) && (!$params->get('urls_position')))) : ?>
                <?php echo $this->loadTemplate('links'); ?>
            <?php endif; ?>

            <?php if ($params->get('access-view')) : ?>
                <?php echo LayoutHelper::render('joomla.content.full_image', $this->item); ?>

                <?php if (isset($info) && $info == 0 && $params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                    <?php echo LayoutHelper::render('joomla.content.info_block', ['item' => $this->item, 'params' => $params, 'position' => 'above']); ?>
                <?php endif; ?>

                <?php if ($info == 0 && $params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                    <?php echo LayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
                <?php endif; ?>

                <div class="article-content" itemprop="articleBody">
                    <?php echo $this->item->text; ?>
                </div>

                <?php if (isset($urls) && ((!empty($urls->urls_position) && $urls->urls_position == '1') || ($params->get('urls_position') == '1'))) : ?>
                    <?php echo $this->loadTemplate('links'); ?>
                <?php endif; ?>
            <?php elseif ($params->get('show_noauth') == true && $this->user->get('guest')) : ?>
                <?php echo LayoutHelper::render('joomla.content.intro_image', $this->item); ?>
                <?php echo HTMLHelper::_('content.prepare', $this->item->introtext); ?>
            <?php endif; ?>

            <?php echo $this->item->event->afterDisplayContent; ?>

            <?php if (isset($info) && ($info == 1 || $info == 2)) : ?>
                <?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                    <?php echo LayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
                <?php endif; ?>
                <?php echo LayoutHelper::render('joomla.content.info_block', ['item' => $this->item, 'params' => $params, 'position' => 'below']); ?>
            <?php endif; ?>
        </div>

        <!-- Table of Contents - Right Side -->
        <div class="col-lg-3 col-md-4 order-md-2 mb-4">
            <div class="sticky-top toc-wrapper" style="top: 20px;">
                <nav id="toc" class="toc-container">
                    <h5 class="toc-title"><?php echo HTMLHelper::_('string.truncate', $this->item->title, 50); ?></h5>
                    <nav id="toc-nav" class="nav flex-column"></nav>
                </nav>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    document.addEventListener('DOMContentLoaded', function() {
        const content = document.querySelector('.article-content');
        const tocNav = document.getElementById('toc-nav');
        
        if (!content || !tocNav) return;
        
        const headings = content.querySelectorAll('h2, h3, h4, h5, h6');
        
        if (headings.length === 0) {
            document.getElementById('toc').style.display = 'none';
            return;
        }
        
        headings.forEach((heading, index) => {
            if (!heading.id) {
                heading.id = 'heading-' + index;
            }
            
            const link = document.createElement('a');
            link.className = 'nav-link';
            link.href = '#' + heading.id;
            link.textContent = heading.textContent;
            
            const level = parseInt(heading.tagName.substring(1));
            link.style.paddingLeft = ((level - 2) * 15 + 10) + 'px';
            
            tocNav.appendChild(link);
        });
        
        tocNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    });
})();
</script>

<style>
.toc-container {
    background: var(--cassiopeia-color-bg, #fff);
    border: 1px solid var(--cassiopeia-color-border, #dee2e6);
    border-radius: 0.375rem;
    padding: 1rem;
}

.toc-title {
    margin-bottom: 0.75rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--cassiopeia-color-text, #212529);
}

#toc-nav .nav-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    color: var(--cassiopeia-color-link, #0d6efd);
    text-decoration: none;
    display: block;
    border-left: 2px solid transparent;
    transition: all 0.2s ease;
}

#toc-nav .nav-link:hover {
    color: var(--cassiopeia-color-hover, #0a58ca);
    background-color: var(--cassiopeia-color-hover-bg, rgba(0, 0, 0, 0.05));
    border-left-color: var(--cassiopeia-color-primary, #0d6efd);
}

#toc-nav .nav-link.active {
    color: var(--cassiopeia-color-primary, #0d6efd);
    border-left-color: var(--cassiopeia-color-primary, #0d6efd);
    background-color: var(--cassiopeia-color-hover-bg, rgba(0, 0, 0, 0.05));
}

@media (max-width: 767.98px) {
    .toc-wrapper {
        position: static !important;
        margin-bottom: 1.5rem;
    }
}
</style>
