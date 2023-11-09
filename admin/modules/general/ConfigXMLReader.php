<?php
class ConfigXMLReader extends AbstractAdvertisementXMLReader{
    protected function parseCategory() {
        if($this->reader->nodeType == XMLREADER::ELEMENT && $this->reader->localName == 'Группы' && $this->reader->depth==2) {
            $node = new SimpleXMLElement($this->reader->readOuterXML());
            $json = json_encode($node);
            $xml = json_decode($json,TRUE);
            $this->result[$this->reader->localName] = $xml;
        }
    }

    protected function parseTovar() {
        if($this->reader->nodeType == XMLREADER::ELEMENT && $this->reader->localName == 'Товар') {
            $node = new SimpleXMLElement($this->reader->readOuterXML());
            $this->result[$this->reader->localName] = $node;
        }
    }

    protected function parseType() {
        if($this->reader->nodeType == XMLREADER::ELEMENT && $this->reader->localName == 'ТипыЦен' && $this->reader->depth==2) {
            $node = new SimpleXMLElement($this->reader->readOuterXML());
            $this->result[$this->reader->localName] = $node;
        }
    }

    protected function parsePrice() {
        if($this->reader->nodeType == XMLREADER::ELEMENT && $this->reader->localName == 'Предложение') {
            $node = new SimpleXMLElement($this->reader->readOuterXML());
            $this->result[$this->reader->localName] = $node;
        }
    }
}