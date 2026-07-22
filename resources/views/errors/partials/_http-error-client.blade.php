@php
    /** @var \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $exception */
    $statusCode = $exception->getStatusCode();
    $raw = trim($exception->getMessage());
    $generic = [
        'Not Found',
        'Forbidden',
        'Unauthorized',
        'Bad Request',
        'Method Not Allowed',
        'Gone',
        'Request Timeout',
        'Conflict',
        'Too Many Requests',
        'Page Expired',
        'Payment Required',
    ];
    // Framework-internal messages (e.g. "The route xy could not be found.") are
    // technical and English-only — replace them with the friendly default too.
    $isInternal = preg_match('/^The route .+ could not be found\.?$/', $raw) === 1;

    $message = ($raw !== '' && ! $isInternal && ! in_array($raw, $generic, true))
        ? $raw
        : __('errors.default_client');
@endphp

@include('errors.partials._error-page', [
    'statusCode' => $statusCode,
    'title' => __('errors.title_client'),
    'message' => $message,
])
