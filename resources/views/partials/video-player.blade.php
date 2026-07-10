@php
    // Accepts either $videoUrl or $videoPath (storage path) or both.
    $src = $videoUrl ?? (isset($videoPath) ? asset('storage/' . $videoPath) : null);

    $isYoutube = $src && (str_contains($src, 'youtube.com') || str_contains($src, 'youtu.be'));
    $isGoogleDrive = $src && str_contains($src, 'drive.google.com');
    $isDirectVideoFile = $src && preg_match('/\.(mp4|webm|ogg)(\?|$)/i', (string) $src);

    $youtubeEmbedUrl = null;
    if ($isYoutube) {
        if (str_contains($src, 'youtu.be')) {
            $parts = explode('/', parse_url($src, PHP_URL_PATH));
            $id = end($parts);
            $youtubeEmbedUrl = 'https://www.youtube-nocookie.com/embed/' . $id;
        } else {
            parse_str(parse_url($src, PHP_URL_QUERY) ?? '', $query);
            $id = $query['v'] ?? null;
            if ($id) {
                $youtubeEmbedUrl = 'https://www.youtube-nocookie.com/embed/' . $id;
            } else {
                $youtubeEmbedUrl = $src;
            }
        }
    }

    $googleDrivePreview = null;
    if ($isGoogleDrive) {
        if (preg_match('/\/d\/([A-Za-z0-9_-]+)/', $src, $m)) {
            $googleDrivePreview = 'https://drive.google.com/file/d/' . $m[1] . '/preview';
        } else {
            $q = parse_url($src, PHP_URL_QUERY);
            if ($q) {
                parse_str($q, $qs);
                if (!empty($qs['id'])) {
                    $googleDrivePreview = 'https://drive.google.com/file/d/' . $qs['id'] . '/preview';
                }
            }
            if (!$googleDrivePreview) {
                $googleDrivePreview = $src;
            }
        }
    }
@endphp

<div class="rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-black">
    @if (!empty($src) && $isYoutube)
        <div class="w-full h-52 md:h-48 bg-black">
            <iframe class="w-full h-full" src="{{ $youtubeEmbedUrl }}" title="Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    @elseif (!empty($src) && $isGoogleDrive)
        <div class="w-full h-52 md:h-48 bg-black">
            <iframe class="w-full h-full" src="{{ $googleDrivePreview }}" title="Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    @elseif (!empty($src) && $isDirectVideoFile)
        <video controls playsinline preload="metadata" class="w-full h-52 md:h-48 object-cover">
            <source src="{{ $src }}" type="video/mp4">
            Browser Anda tidak mendukung pemutaran video.
        </video>
    @elseif (!empty($src))
        <video controls playsinline preload="metadata" class="w-full h-52 md:h-48 object-cover">
            <source src="{{ $src }}">
            Browser Anda tidak mendukung pemutaran video.
        </video>
    @else
        <div class="h-52 md:h-48 flex items-center justify-center text-zinc-300 text-sm uppercase tracking-widest">Tanpa Video</div>
    @endif
</div>
