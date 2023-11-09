<?
class mime_mail
{
    public $parts;
    public $to;
    public $from;
    public $headers;
    public $subject;
    public $body;

    public function mime($str, $data_charset = 'utf-8', $send_charset = 'utf-8')
    {
        if ($data_charset != $send_charset) {
            $str = iconv($data_charset, $send_charset, $str);
        }

        return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
    }

    public function mime_mail()
    {
        $this->parts   = array();
        $this->to      = "";
        $this->from    = "";
        $this->subject = "";
        $this->body    = "";
        $this->headers = "";
    }
    public function add_attachment($message, $name = "", $ctype = "image/jpeg")
    {
        $this->parts[] = array(
            "ctype"   => $ctype,
            "message" => $message,
            "encode"  => $encode,
            "name"    => $name);
    }
    public function build_message($part)
    {
        $message  = $part["message"];
        $message  = chunk_split(base64_encode($message));
        $encoding = "base64";
        return "Content-Type: " . $part["ctype"] . ($part["name"] ? "; name = \"" . $part["name"] . "\"" : "") . "\nContent-Transfer-Encoding: $encoding\n\n$message\n";
    }
    public function build_multipart()
    {
        $boundary  = "b" . md5(uniqid(time()));
        $multipart = "Content-Type: multipart/mixed; boundary = $boundary\n\nThis is a MIME encoded message.\n\n--$boundary";
        for ($i = sizeof($this->parts) - 1; $i >= 0; $i--) {
            $multipart .= "\n" . $this->build_message($this->parts[$i]) . "--$boundary";
        }

        return $multipart .= "--\n";
    }
    public function send()
    {
        $mime = "";
        if (!empty($this->from)) {
            $mime .= "From: " . $this->from . "\n";
        }

        if (!empty($this->headers)) {
            $mime .= $this->headers . "\n";
        }

        if (!empty($this->body)) {
            $this->add_attachment($this->body, "", " text/html; charset=utf-8");
        }

        $mime .= "MIME-Version: 1.0\n" . $this->build_multipart();
        $i = mail($this->to, $this->subject, "", $mime);
        if (!$i) {echo "<script>alert('Îøèáêà îòïðàâêè!');</script>";}
    }
}

/*$attachment = fread(fopen($_SERVER['DOCUMENT_ROOT']."/bank/uploads/2222.txt", "r"), filesize($_SERVER['DOCUMENT_ROOT']."/bank/uploads/2222.txt"));
$mail = new mime_mail();
$mail->from = "ivan@applepages.kz";
$mail->headers = "Errors-To: [EMAIL=ivan@applepages.kz]ivan@applepages.kz[/EMAIL]";
$mail->to = "ivan@applepages.kz";
$mail->subject = "PHP atachment";
$mail->body = "Ýòî òåêñò <b>ìîé</b>";
$mail->add_attachment("$attachment", "2222.txt", "Content-Transfer-Encoding: base64 /9j/4AAQSkZJRgABAgEASABIAAD/7QT+UGhvdG9zaG");
$mail->send();
 */
