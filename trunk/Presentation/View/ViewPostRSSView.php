<?php

  //this class represents a collection (array, to be specific) of posts, and can display them as RSS
class Presentation_View_ViewPostRSSView extends Presentation_View_View
{
    private $posts;
    private $title;
    private $bloglink;
    private $postlinkprefix;
    private $about;
    
    public function __construct($posts)
    {
        if (!is_array($posts))
        {
            throw new Exception("ViewPostRSSView must be passed an array of ViewPostViews");
        }
        $this->posts = $posts;
    }

    public function AddBlogInfo($title, $about, $bloglink, $postlinkprefix) {
        $this->title = $title;
        $this->about = $about;
        $this->bloglink = $bloglink;
        $this->postlinkprefix = $postlinkprefix;
    }

    public function Display()
    {
        //blog information:
        $ret = "<?xml version=\"1.0\"?>\n"
            ."<rss version=\"2.0\"><channel>\n"
            .'<title>'.$this->title."</title>\n"
            .'<link><![CDATA['.$this->bloglink."]]></link>\n"//firefox freaks out with the "blogid=n" bit
            .'<description>'.$this->about."</description>\n"
            ."<docs>http://blogs.law.harvard.edu/tech/rss</docs>\n"
            ."<generator>ACLPS</generator>\n";
        if (is_array($this->posts))
        {
            foreach($this->posts as $value)
            {
                $ret .= '<item><title>'.$value->GetTitle().'</title>'
                    .'<author>'.$value->GetAuthorName().'</author>'
                    .'<link><![CDATA['.$this->postlinkprefix.$value->GetPostID().']]></link>'
                    .'<description>'.strip_tags($value->GetHTMLContent()).'</description>'
                    .'<pubDate>'.date("r", strtotime($value->GetTimestamp())).'</pubDate>'
                    .'<guid><![CDATA['.$this->postlinkprefix.$value->GetPostID()."]]></guid></item>\n";
            }
        }
        elseif (isset($this->posts))
        {
            throw new Exception("Contents of ViewPostCollectionView must either be an array or unset.");
        }
        $ret .= '</channel></rss>';
        return $ret;
    }
}

?>
