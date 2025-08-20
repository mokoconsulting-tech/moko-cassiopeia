<?php

/**
 * @package Tabaoca.Component.Gabble.Site
 * @subpackage mod_gabble
 * @copyright (C) 2023 Jonatas C. Ferreira
 * @license GNU/AGPL v3 (see licence.txt)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$currentuser = Factory::getuser();
$app = Factory::getApplication();
$session = $app->getSession();
$config = $app->getParams('com_gabble');
$document = Factory::getDocument();
$document->addStyleSheet('media/com_gabble/css/gabble.css');
$document->addStyleSheet('media/templates/site/cassiopeia_meaewellness/css/gable.css');
$lang = Factory::getLanguage();
$lang->load('com_gabble');

Text::script('COM_GABBLE_TIMEOUT');

if ( !$currentuser->get("id")){

   echo '<div class="content">
			<div id="mod_gable">
				<div id="mod_lists_gabble">
					<div class="taba-user-on"><div class="taba-user"><i class="icon-joomla"></i> '. Text::_('COM_GABBLE_LOGGEDIN') . '</div></div>
				</div>
			</div>
		</div>';

   return;

}

$input = $app->input;

if ($input->get('option') == 'com_gabble') {

	echo '<div class="content">
			<div id="mod_gabble">
				<div id="mod_lists_gabble">
					<button id="btn_gabble" class="button_list" onclick="window.location.href = &quot;' . Uri::root() . '&quot;;"><i class="icon-home"></i></button>
					<div class="taba-user-on"><div class="taba-user"><i class="icon-joomla"></i> '. Text::_('COM_GABBLE_GABBLE_CHAT') . '</div></div>
				</div>
			</div>
		</div>';

	return;

}

$document->addScript('media/com_gabble/js/gabble_com.js');
//$document->addScript('media/templates/site/cassiopeia_meaewellness/js/mod_gabblegabble_com.js');
?>

<div class="content">

	<div id="mod_gabble">

		<div id="list_windows" class="list-windows"></div>
		<div id="main_windows" class="main-windows"></div>

		<div id="lists_gabble">

			<div id="select_list" class="row">

				<div class="col-md-4 notifications" title="<?php echo Text::_('COM_GABBLE_CHATS'); ?>">
					<button id="list_chats" class="button_list" title="Chats" onclick="select_list(1);"><i class="icon-comments-2"></i></button>
					<div id="n_notifications" class="n-notifications" title="Users" hidden=""></div>
				</div>
				<div class="col-md-4" title="<?php echo Text::_('COM_GABBLE_USERS'); ?>">
					<button id="list_users" class="button_list" onclick="select_list(2);"><i class="icon-users"></i></button>
				</div>
				<div class="col-md-4" title="<?php echo Text::_('COM_GABBLE_GABBLE_CHAT'); ?>">
					<button id="btn_gabble" class="button_list" onclick="window.location.href = &quot;<?php echo Uri::root().'index.php?option=com_gabble&view=gabble'; ?>&quot;;"><i class="icon-expand-2"></i></button>
				</div>

			</div>

			<div id="options_list" hidden="">
				<select id="users_list" name="users_list" onchange="select_list(2);">
					<option value="0"><?php echo Text::_('COM_GABBLE_USERS_ON'); ?></option>
					<option value="1"><?php echo Text::_('COM_GABBLE_USERS_ALL'); ?></option>
				</select>
			</div>

			<div id="frame_list">
				<iframe
					id="users_frame"
					class="iframe_list"
					srcdoc="<html>
								<head>
									<link rel=&quot;stylesheet&quot; href=&quot;<?php echo Uri::root().'media/com_gabble/css/gabble.css'; ?>&quot; type=&quot;text/css&quot;/>
								</head>
								<body class=&quot;taba-content&quot;>
								</body>
							</html>"
					marginwidth="0"
					marginheight="0"
					onload="setup_com();"
					hidden="">
				</iframe>
				<iframe
					id="users_on_frame"
					class="iframe_list"
					srcdoc="<html>
								<head>
									<link rel=&quot;stylesheet&quot; href=&quot;<?php echo Uri::root().'media/com_gabble/css/gabble.css'; ?>&quot; type=&quot;text/css&quot;/>
								</head>
								<body class=&quot;taba-content&quot;>
								</body>
							</html>"
					marginwidth="0"
					marginheight="0"
					hidden="">
				</iframe>
				<iframe
					id="feeds_frame"
					class="iframe_list"
					srcdoc="<html>
								<head>
									<link rel=&quot;stylesheet&quot; href=&quot;<?php echo Uri::root().'media/com_gabble/css/gabble.css'; ?>&quot; type=&quot;text/css&quot;/>
								</head>
								<body class=&quot;taba-content&quot;>
								</body>
							</html>"
					marginwidth="0"
					marginheight="0"
					hidden="">
				</iframe>
			</div>

			<div id="openai_btn" title="OpenAi GPT" onclick="open_user(0); event.stopPropagation();">
				<img src="<?php echo Uri::root() . "media/com_gabble/images/logo_openai.png"; ?>" alt="OpenAI GPT">
			</div>

		</div>

	</div>

	<input type="hidden" id="gabble_type" value="mod">
	<input type="hidden" id="uri_root" value="<?php echo Uri::root(); ?>">
	<input type="hidden" id="token" value="<?php echo $session->getFormToken(); ?>">
	<input type="hidden" id="user_id" value="<?php echo $currentuser->get("id"); ?>">

	<input type="hidden" id="openai_gpt" value="<?php echo $config->get('openai_gpt'); ?>">
	<input type="hidden" id="openai_gpt_name" value="<?php echo $config->get('openai_gpt_name'); ?>">


</div>

<p style="text-align:right;" ><?php echo Text::_('COM_GABBLE_POWERED');?> <a href="https://tabaoca.org">Tabaoca</a></p>

