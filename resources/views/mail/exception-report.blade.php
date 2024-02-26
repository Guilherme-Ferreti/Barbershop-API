<x-mail::message>
The following exception has been reported:

# Message

{{ $exception->getMessage() }}

# File

{{ $exception->getFile() }}

# Line

{{ $exception->getLine() }}

# Hour

{{ now()->format('Y-m-d H:i:s') }}
</x-mail::message>
