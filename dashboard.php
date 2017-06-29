<?php

use SheDied\SheDieDConfig;
use SheDied\PojokJogjaController;
use SheDied\parser\Collections;

if (!function_exists('file_get_contents')) {
    echo "<span color='red'>This Hosting not support file_get_content. This Plugin will not works here<span>";
}

function shedied_create_posts($count = 10) {
    try {
        $sources = SheDieDConfig::getSourcesList();
        $controller = new PojokJogjaController(new Collections());
        $controller->setNewsSrc($_POST['news_src'])
                ->setBulkPostType($_POST['bulk_post_type'])
                ->setCategory($_POST['category'])
                ->setAuthor($_POST['author'])
                ->setBulkPostStatus($_POST['bulk_post_status'])
                ->setInterval($_POST['interval'])
                ->setUrl($sources[$_POST['news_src']]['url'])
                ->setAction($_POST['action'])
                ->setCount($count)
                ->hijack(false)
                ->isAuto(false);
        $controller->buildPosts();
    } catch (\Exception $ex) {
        syslog(LOG_ERR, '[shedied poster] ' . $ex->getMessage());
    }
}

function shedied_gen_news_combo($id) {
    $str = '<select name="' . $id . '" id="' . $id . '">';
    $sources = SheDieDConfig::getSourcesList();
    foreach ($sources as $key => $source) {
        $str .= '<option value="' . $key . '">' . $source['name'] . '</option>';
    }
    $str .= '</select>';
    return $str;
}

function shedied_my_panel() {
    $version = "6.6.6";
    if (isset($_POST['action'])) {
        shedied_create_posts();
        exit;
    }

    echo '<div class="wrap">';
    echo '
	    <div id="icon-options-general" class="icon32">
		<br></div>
		<h2>SheDied ' . $version . '</h2>
	    <div id="tabs">
	 		<ul>
			    <li><a href="#tabs-Home">Home</a></li>
				<li><a href="#tabs-Setting">Setting</a></li>
				<li><a href="#tabs-Campaign">Campaign</a></li>
			    <li><a href="#tabs-About">About</a></li>
	  		</ul>
		  <div id="tabs-Home">';

    echo '<form name="frmPost" method="post">' . PHP_EOL;
    $year = date("Y");
    echo '<table style="text-align: left; padding: 10px 30px;">
			<tr valign="top">
				<th scope="row">News Source</th>
				<td>
					';
    echo shedied_gen_news_combo("news_src");
    echo '					
				</td>						

			</tr>

			<tr valign="top">
				<th scope="row">Post Type</th>
				<td>
					<select name="bulk_post_type">
						<option value="post">Posts</option>
						<option value="page">Pages</option>
					</select>
				</td>						

			</tr>
			<tr><th>Category</th> <th>';
    wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'category', 'orderby' => 'name',
        'selected' => "", 'hierarchical' => true, 'show_option_none' => __('None')));
    echo "Authors:";
    wp_dropdown_users(array('name' => 'author', 'selected' => get_current_user_id()));
    echo '</th></tr>
			<tr valign="top">
				<th scope="row">Post Status</th>
				<td>
					<select name="bulk_post_status" id="bulk_post_status">
						<option value="publish">Published</option>
						<option value="draft">Draft</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Start Posting On</th>
				<td>';
    echo shedied_show_year();
    echo shedied_show_month();
    echo shedied_show_day();
    ?>
    </td>
    </tr>
    <tr valign="top">
        <th scope="row">Post Every</th>
        <td>
            <input type="text" value="1" name="interval[value]" style="width:40px;">
            <select name="interval[type]">
                <option value="minutes">Minute</option>
                <option value="hours">Hour</option>
                <option value="days">Day</option>
            </select>				
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <input type="submit" class="button-primary" value="Create Bulk Post" />
        </td>
    </tr>
    <!--tr>
            <th><br>CRON URL</th>
            <td><br-->
    <?php
    //echo '<a href="'.plugin_dir_url(__FILE__).'cron.php" target=_blank>Test CRON</a>'.PHP_EOL;
    //echo '				</td>		</tr>';
    echo '</table>' . PHP_EOL;

    echo '<input type="hidden" name="action" value="update" />' . PHP_EOL;
    echo '
			</form>' . PHP_EOL;

    $firstPara = get_option("shedied_firstpara");
    $lastPara = get_option("shedied_lastpara");
    $isAutopost = get_option("shedied_isAutopost");
    $isRewrite = get_option("shedied_isRewrite");
    $isFullSource = get_option("shedied_isFullSource");
    $isRemoveLink = get_option("shedied_isRemoveLink");
    $isTitleRewrite = get_option("shedied_isTitleRewrite");


    if ($isFullSource == "true") {
        $isFullSource = " checked";
    } else
        $isFullSource = "";

    if ($isRemoveLink == "true") {
        $isRemoveLink = " checked";
    } else
        $isRemoveLink = "";

    if ($isRewrite == "true") {
        $isRewrite = " checked";
    } else
        $isRewrite = "";

    if ($isAutopost == "true") {
        $isAutopost = " checked";
    } else
        $isAutopost = "";

    if ($isTitleRewrite == "true") {
        $isTitleRewrite = " checked";
    } else
        $isTitleRewrite = "";


    echo '		  </div>
	<div id="tabs-Setting" style="min-height:200px">
	<h3>Setting</h3>	
	<table style="text-align: left; padding: 10px 30px;">
				<tr valign="top">
					<th scope="row">First Paragraph</th>
					<td><textarea  id="firstPara" name="firstPara" cols=70 rows=6>' .
    $firstPara
    .
    '</textarea>
					<br>
					Use this paragraph to add paragraph in the first/last post so it will be diffrent than the source
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Last Paragraph</th>
				<td>
					<textarea id="lastPara" name="lastPara" cols=70 rows=6>' .
    $lastPara
    . '</textarea>
					<br> You can Use {TITLE} or {CATEGORY} here
				</td>
				</tr>
				<tr>
				<th scope="row">Enable Content Rewrite?</th>
				<td>
				<input type="checkbox" id="ckRewrite" name="ckRewrite" ' . $isRewrite . '/>  Indonesian News Only
				</td>
				</tr>
				<!--tr>
				<th scope="row">Enable Title Rewrite?</th>
				<td>
				<input type="checkbox" id="ckTitleRewrite" name="ckTitleRewrite" ' . $isTitleRewrite . '/>  Indonesian News Only				
				</td>
				</tr-->
				<tr>
				<th scope="row">Enable Autopost?</th>
				<td>
				<input type="checkbox" id="ckAutoPost" name="ckAutoPost" ' . $isAutopost . '/> AutoPost every 2Hours
				</td>
				</tr>
				<tr>
				<th scope="row">Remove Link on Content</th>
				<td>
				<input type="checkbox" id="ckRemoveLink" name="ckRemoveLink" ' . $isRemoveLink . '/> Yes
				</td>
				</tr>
				<tr>
				<th scope="row">News Source URL</th>
				<td>
				<input type="checkbox" id="ckFullSource" name="ckFullSource" ' . $isFullSource . '/> Shows site Domain only instead full source URL
				</td>
				</tr>
				<tr>
				<th scope="row"></th>
				<td align="center">';
    echo "
				<input type='button' class='button-primary' value='Save Setting' id='btnSetting' name='btnSetting' onclick='do_ajax(\"save_setting\")'/>
				</td>
				</tr>";

    echo '
	</table>
	</div>	
	';
    echo '<div id="tabs-Campaign">
			<h3 class="hndle"><span>Auto Post Campaign</span></h3>
			<table>
				<tr>
				<th scope="row"></th>
				<td>
				Source: ';
    echo shedied_gen_news_combo("set_news_src");
    echo "Category";
    wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'set_category', 'orderby' => 'name',
        'selected' => "", 'hierarchical' => true, 'show_option_none' => __('None')));
    echo "Authors";
    wp_dropdown_users(array('name' => 'set_author', 'selected' => get_current_user_id()));
    echo "<input type='button' class='button-primary' value='Add Campaign' onclick='do_ajax(\"add_campaign\")'>";
    echo '
				</td>
			</tr>
			<tr>
			<th scope="row" valign="top">Campaign</th>
			<td><div id="z_campaign"></div></td>
			</tr>	
			</table>
		  </div>
		 ';

    echo '<div id="tabs-About" style="width:200px">
  							<div class="postbox" id="sm_pnres">
								<h3 class="hndle" style="padding-left: 12px;"><span>About</span></h3>
								<div class="inside">
									<ul>
									<li><a href="http://www.shedied.xyz" class="sm_button sm_pluginHome">Plugin Homepage</a></li>
									<li><strong>License:</strong> Personal Use Only
									</li>
									<li><strong>Support:</strong> +62 857 257 962 36
									</li>

									</ul>
								</div>
							</div>
	
		  </div>


		</div>	<!-- end tabs -->   
			  
		<div class="modal"><!-- Place at bottom of page --></div> 
	    ';

    echo "</div> <!-- end wrap -->";
}

function shedied_show_year() {
    $cur_year = date("Y");
    $str = '<select name="date[year]">';
    FOR ($currentMonth = $cur_year + 2; $currentMonth >= $cur_year - 2; $currentMonth--) {
        $str .= "<OPTION VALUE=\"";
        $str .= INTVAL($currentMonth);
        $str .= "\"";
        IF ($currentMonth == $cur_year) {
            $str .= " SELECTED";
        }
        $str .= ">" . $currentMonth . "\n";
    }
    $str .= "</SELECT>";
    return $str;
}

function shedied_show_month() {
    $monthName = ARRAY(1 => "January", "February", "March",
        "April", "May", "June", "July", "August",
        "September", "October", "November", "December");
    $useDate = TIME();

    $str = '<select name="date[month]">';
    FOR ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
        $str .= "<OPTION VALUE=\"";
        $str .= INTVAL($currentMonth);
        $str .= "\"";
        IF (INTVAL(DATE("m", $useDate)) == $currentMonth) {
            $str .= " SELECTED";
        }
        $str .= ">" . $monthName[$currentMonth] . "\n";
    }
    $str .= "</SELECT>";
    return $str;
}

function shedied_show_day() {
    $useDate = TIME();
    $str = '<select name="date[day]">';
    FOR ($currentDay = 1; $currentDay <= 31; $currentDay++) {
        $str .= " <OPTION VALUE='$currentDay'";
        IF (INTVAL(DATE("d", $useDate)) == $currentDay) {
            $str .= " SELECTED";
        }
        $str .= ">$currentDay\n";
    }
    $str .= "</SELECT>";
    return $str;
}
