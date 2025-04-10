<?php
    function matchRouteAndExtractId($pattern, $requestURI) {
        if (preg_match($pattern, $requestURI, $matches)) {
            return [
                'matched' => true,
                'id' => isset($matches[1]) ? (int)$matches[1] : null
            ];
        }
        return ['matched' => false, 'id' => null];
    }

    function sendResponse($response, $httpCode = 200) {
        http_response_code($httpCode);
        echo json_encode($response);
    }
?>