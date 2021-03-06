<?php
/**
 * 虾米音乐播放器 Hermit for typecho xiami music player
 * 
 * @package Hermit 
 * @author mufeng
 * @version 1.0.4
 * @update: 2014.4.17
 * @link http://mufeng.me/
 */
class Hermit_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
		/** 添加文章 */
        Typecho_Plugin::factory('admin/write-post.php')->bottom = array('Hermit_Plugin', 'render');
		Typecho_Plugin::factory('admin/write-page.php')->bottom = array('Hermit_Plugin', 'render');
		
        /** 前端输出处理接口 */
        Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('Hermit_Plugin', 'parse');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('Hermit_Plugin', 'parse');
		Typecho_Plugin::factory('Widget_Archive')->header = array('Hermit_Plugin', 'headerScript');
		Typecho_Plugin::factory('Widget_Archive')->footer = array('Hermit_Plugin', 'footerScript');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 文章页添加一个发布音乐按钮
     * 
     * @access public
     * @return void
     */
    public static function render()
    {
		$options = Helper::options();
		$img_url = Typecho_Common::url('Hermit/assets/images/iconx.png', $options->pluginUrl);
		$css_url = Typecho_Common::url('Hermit/assets/style/hermit.admin.css', $options->pluginUrl);
		$js_url = Typecho_Common::url('Hermit/assets/script/hermit.admin.js', $options->pluginUrl);
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$css_url}\" />\n";
		echo "<script>var hermit_img_url = \"{$img_url}\";\n</script><script src='{$js_url}'></script>\n";
    }

    /**
     * 短代码实现方法
     * 歌曲列表 [hermit auto="1" loop="1"]songlist#:1772276934,1772276930,1772276933[/hermit]
     * 专辑 [hermit auto="1" loop="1"]album#:1772276934[/hermit]
     * 精选集 [hermit auto="1" loop="1"]collect#:28721332[/hermit]
     * 
     * @access public
     * @return void
     */
    public static function parse($text, $widget, $lastResult)
    {
        $text = empty($lastResult) ? $text : $lastResult;
        if ($widget instanceof Widget_Archive) {
			$text = preg_replace("/\[hermit(.+?)?\](.+?)\[\/hermit\]/i",
            "<!--Hermit for typecho v1.0.0 start--><div class=\"hermit\" \\1 songs=\"\\2\"><div class=\"hermit-box\"><div class=\"hermit-controls\"><div class=\"hermit-button\"></div><div class=\"hermit-detail\">单击鼠标左键播放或暂停。</div><div class=\"hermit-duration\"></div><div class=\"hermit-listbutton\"></div></div><div class=\"hermit-prosess\"><div class=\"hermit-loaded\"></div><div class=\"hermit-prosess-bar\"><div class=\"hermit-prosess-after\"></div></div></div></div><div class=\"hermit-list\"></div></div><!--Hermit for typecho v1.0.0 end-->",
            $text);
        }
        return $text;
    }

	/**
     * 顶部CSS加载
     * 
     * @access public
     * @return void
     */
    public static function headerScript()
    {
		$options = Helper::options();
		$css_url = Typecho_Common::url('Hermit/assets/style/hermit.min.css', $options->pluginUrl);
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$css_url}\" media=\"all\" />\n";	
    }
	
	/**
     * 底部Javascript加载
     * 
     * @access public
     * @return void
     */
    public static function footerScript()
    {
		$options = Helper::options();
		$swf_url = Typecho_Common::url('Hermit/assets/swf', $options->pluginUrl);
		$js_url = Typecho_Common::url('Hermit/assets/script/hermit.min.js', $options->pluginUrl);
		echo "<script>var hermit = {url: \"{$swf_url}\"};\n</script><script src='{$js_url}'></script>\n";	
    }
}
