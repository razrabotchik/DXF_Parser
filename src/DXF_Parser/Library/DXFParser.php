<?php

/**
 * Class DXFParser
 */
class DXFParser {

    /** @var null|array */
    protected $_names = null;

    /**
     * @param $file
     * @return bool
     * @throws Exception
     */
    public function load($file) {
        if (!file_exists($file)) {
            throw new \Exception('File ' . $file . ' not found');
        }

        $file = fopen($file, 'r');

        $content = '';
        do {
            $content .= trim(fgets($file));
            $content .= ":";
            $content .= trim(fgets($file));
            $content .= "\n";

            if (feof($file)) {
                break;
            }
        } while(true);

        fclose($file);

        return $this->parse($content);
    }

    /**
     * @param $string
     * @return bool
     * @throws Exception
     */
    public function parse($string) {
        if (empty($string)) {
            throw new \Exception('File is empty');
        }

        $lines = preg_split('#\n\r|\r|\n#', $string);
        unset($string);

        $names = array();

        $nameStart = false;
        foreach ($lines as $position => $line) {

            if ($line == '0:TEXT') {
                if ($nameStart) {
                    // end prev name and start new
                } else {
                    // start new name
                    $nameStart = true;
                }
            }

            if ($nameStart) {
                if (preg_match('#1:(.*)$#', $line, $matches) && preg_match('#piece name:(.*)$#is', trim($matches[1]), $matches)) {
                    $names[$position] = trim($matches[1]);
                }
            }
        }
        unset($lines);

        $this->_names = $names;

        return true;
    }

    /**
     * @return array
     */
    public function getNames() {
        return $this->_names;
    }

}