    public function get{{CONSTANT_FUNCTION_NAME}}Name()
    {
        $name = '';

        switch ($this->{{CONSTANT_PROPERTY}}) {
{{CONSTANT_CASE}}
        }

        return $name;
    }

    public static function get{{CONSTANT_FUNCTION_NAME}}List()
    {
        $lang = DI::getDefault()->get('lang');

        return $data = [
{{CONSTANT_LIST}}
        ];
    }

    public static function get{{CONSTANT_LABEL_NAME}}ListArray()
    {
        return [
{{CONSTANT_LISTARRAY}}
        ];
    }
