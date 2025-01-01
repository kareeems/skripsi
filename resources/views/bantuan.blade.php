@extends('layouts.frontend')

@section('content')
        <h3>Bantuan</h3>
        <div class="d-flex">
            <button class="btn btn-primary tab-button me-2">Panduan</button>
            <button class="btn btn-outline-secondary tab-button">Pertanyaan Umum</button>
        </div>

        <div class="pdf-viewer mt-3">
            <!-- Embed PDF Viewer -->
            <iframe src="https://docs.google.com/viewer?url=https://example.com/sample.pdf&embedded=true" 
                width="100%" height="100%" frameborder="0"></iframe>
        </div>
@endsection