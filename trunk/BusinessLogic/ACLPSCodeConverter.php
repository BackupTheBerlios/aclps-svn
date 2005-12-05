<?php

class BusinessLogic_ACLPSCodeConverter
{
    private static $simpleConversions = array(  '[b]'           => '<b>',
                                                '[/b]'          => '</b>',
                                                '[i]'           => '<i>',
                                                '[/i]'          => '</i>',
                                                '[u]'           => '<u>',
                                                '[/u]'          => '</u>',
                                                "\n"            => '<br />',
                                                '[title]'       => '<h1>',
                                                '[/title]'      => '</h1>',
                                                '[subtitle]'    => '<h2>',
                                                '[/subtitle]'   => '</h2>');
    public static function ACLPSCodeToHTML($content)
    {
        //simple cases
        foreach(self::$simpleConversions as $search=>$replace)
        {
            $content = str_replace($search, $replace, $content);
        }
        
        //[img]URL[/img] => <img src=URL>
        while(strstr($content, '[img]'))
        {
        	$sub = strstr($content, '[img]');
        	unset($offset);
        	$offset = strpos($sub, '[/img]');
        	if (isset($offset))
        	{
        		$URL = substr($sub, 5, $offset - 5);
        		$content = str_replace('[img]' . $URL . '[/img]', '<img src=' . $URL . '>' , $content);
        	}
        	else
        	{
        		$content = str_replace('[img]', '', $content);
        	}
        }
        
        unset($sub);
        unset($offset);
        
        //[link=URL]name[/link]
        while(strstr($content, '[link='))
        {
        	$sub = strstr($content, '[link=]');
        	unset($offset);
        	$offset = strpos($sub, ']');
        	if (isset($offset))
        	{
        		$URL = substr($sub, 6, $offset - 6);
        		
        		$subsub = strstr($content, 'link=' . $URL . ']');
        		
        		$content = str_replace('[img]' . $URL . '[/img]', '<img src=' . $URL . '>' , $content);
        	}
        	else
        	{
        		$content = str_replace('[img]', '', $content);
        	}
        }
        
        return $content;
    }
}

?>
