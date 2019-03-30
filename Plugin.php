<?php
/**
 * 评论通知推送至 IFTTT Webhooks
 *
 * @package Comment2IFTTT
 * @author 神代綺凜
 * @version 1.0.0
 * @link https://moe.best
 */
class Comment2IFTTT_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Feedback')->comment = array('Comment2IFTTT_Plugin', 'whSend');
        return _t('请记得进入插件配置 IFTTT Webhooks key');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate() {}

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form) {
        $key = new Typecho_Widget_Helper_Form_Element_Text('whKey', NULL, NULL, _t('Webhooks Key'), _t('想要获取 Webhooks key 则需要启用 <a href="https://ifttt.com/maker_webhooks" target="_blank">IFTTT 的 Webhooks 服务</a>，然后点击右上角的“Documentation”来查看'));
        $form->addInput($key->addRule('required', _t('您必须填写 Webhooks key')));

        $event = new Typecho_Widget_Helper_Form_Element_Text('evName', NULL, NULL, _t('Event Name'), _t('Webhooks 事件名'));
        $form->addInput($event->addRule('required', _t('您必须填写 Event Name')));

        $excludeBlogger = new Typecho_Widget_Helper_Form_Element_Radio('excludeBlogger',
            array(
                '1' => '是',
                '0' => '否'
            ),'1', _t('当评论者为博主时不推送'), _t('启用后，若评论者为博主，则不会推送至 IFTTT Webhooks'));
        $form->addInput($excludeBlogger);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form) {}

    /**
     * 推送至 IFTTT Webhooks
     *
     * @access public
     * @param array $comment 评论结构
     * @param Typecho_Widget $post 被评论的文章
     * @return $comment
     */
    public static function whSend($comment, $post) {
        $options = Typecho_Widget::widget('Widget_Options')->plugin('Comment2IFTTT');

        $whKey = $options->whKey;
        $evName = $options->evName;
        $excludeBlogger = $options->excludeBlogger;

        if ($comment['authorId'] == 1 && $excludeBlogger == '1') {
            return $comment;
        }

        $headers = array(
            "Content-type: application/json"
        );
        $url = 'https://maker.ifttt.com/trigger/'.$evName.'/with/key/'.$whKey;
        $data = array(
            'value1' => $post->title,
            'value2' => $comment['author'],
            'value3' => $comment['text']
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_exec($ch);
        curl_close($ch);

        return $comment;
    }
}
