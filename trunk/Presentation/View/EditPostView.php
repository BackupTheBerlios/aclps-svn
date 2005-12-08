<?php

class Presentation_View_EditPostView extends Presentation_View_View
{
    private $post;
    
    public function __construct($post,$defaulttitle,$defaultcontent,$defaultpublic,$errmsg)
    {
        if (is_array($post))
        {
            throw new Exception("EditPostViews only support a single ViewPostView");
        }
        $this->post = $post;
        $this->errmsg = $errmsg;
        if (strlen($defaulttitle) > 0)
        {
            $this->post->SetTitle($defaulttitle);
        }
        if (strlen($defaultcontent) > 0)
        {
            $this->post->SetContent($defaultcontent);
        }
        //2 = dont modify post's current public status 
        if ($defaultpublic != 2) {
            $this->post->SetPublic($defaultpublic);
        }
    }

    public function Display()
    {
        if ($this->post->GetPublic())
        {
            $checkmark = 'checked';
        }
        else
        {
            $checkmark = '';
        }
        $form = '<fieldset><legend><img src="UI/Themes/Images/Controls/editpost.png" id="controlbarimg" /> Edit Post</legend>'
            . '<form method="post" action="index.php?blogID='.$this->post->GetBlogID().'&Action=ProcessEditPost">';

        if (strlen($this->errmsg) > 0)
        {
            $form .= '<p>'.$this->errmsg.'</p>';
        }

        $form .= '<input type="hidden" name="postID" value="'.$this->post->GetPostID().'">'

            . '<table id="formtable"><tr><td colspan="2"><label for="title">Title:</label>'
            . '<input type="text" name="title" maxlength="30" size="30" value="'.htmlspecialchars($this->post->GetTitle()).'"></td></tr>'

            . '<tr><td><input type="checkbox" name="public" '.$checkmark.'></td>'
            . '<td><label for="public">Public (Anonymous can view)</label></td></tr>'

            . '<tr><td colspan="2"><label for="timestamp">Timestamp:</label></td></tr>'
            . '<tr><td><input type="radio" name="timestamp" value="now"></td><td>Change To Now</td></tr>'
            . '<tr><td><input type="radio" name="timestamp" value="orig" checked></td><td>Leave Original ('.$this->post->GetTimestamp().')</td></tr>'

            . '<tr><td colspan="2" valign="middle"><label for="content">Content:</label></td></tr>'
            . '<tr><td colspan="2"><textarea name="content" rows="15" cols="60">'.htmlspecialchars($this->post->GetACLPSContent()).'</textarea></td></tr>'

            . '<tr><td colspan="2" align="center"><input type="submit" id="submit" value="Edit Post"></td></tr></table>'
            . '</form></fieldset>';

        return $form;
    }
}

?>
