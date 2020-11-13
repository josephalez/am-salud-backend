<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class CustomizeFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        $request = request();

        $param="\t";
        
        foreach ($request->all() as $key => $value) {
            $param.=$key." = ".$value."\n\t";
        }

        $obj=new LineFormatter(
                "[%datetime%] %channel%.%level_name%:\n %message%\n %context% %extra% ".
                "\n Url =====> ".$request->fullUrl().
                "\n method ===> ".$request->method().
                "\n Param ++++++++++++++++++++\n".
                //json_encode($request->all()).
                $param.
                "\n Header ++++++++++++++++++++\n".
                json_encode($request->header()).
                //$header.
                "\n===========++++++++++++++++++++++++++=============\n".
                "===========++++++++++++++++++++++++++=============\n".
                "===========++++++++++++++++++++++++++=============\n"
            );
        $obj->includeStacktraces(true);
        $obj->allowInlineLineBreaks(true);
        $obj->ignoreEmptyContextAndExtra(true);
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($obj);
        }
    }
}