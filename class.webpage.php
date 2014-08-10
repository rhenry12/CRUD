<?php #Webpage class
class Webpage {

    protected $title;
    private $content;
    
    function __construct($title) {
        $this->title = $title;
    }
    
    /*Construct a basic html page*/
    public function display() {
        echo
        "
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset='UTF-8'>
                <title>$this->title</title>
                <link rel='stylesheet' href='reset.css' type='text/css' media='screen'>
                <link rel='stylesheet' href='design.css' type='text/css' media='screen'>
            </head>
            <body>
        ".
                $this->getContent()
        ."
            </body>
        </html>
        ";
    }
    
    public function setContent($data) {
        $this->content = $data;
    }
    
    private function getContent() {
        return $this->content;
    }
}
?>