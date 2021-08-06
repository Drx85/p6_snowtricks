<?php


namespace App\Service;


class UrlToEmbedUrl
{
	public function embedUrl($url)
	{
		if (str_contains($url, 'youtube') || str_contains($url, 'youtu.be')) {
			$shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
			$longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';
			
			if (preg_match($longUrlRegex, $url, $matches)) {
				$youtube_id = $matches[count($matches) - 1];
			}
			
			if (preg_match($shortUrlRegex, $url, $matches)) {
				$youtube_id = $matches[count($matches) - 1];
			}
			if (isset($youtube_id)) return 'https://www.youtube.com/embed/' . $youtube_id;
		}
		
		if (str_contains($url, 'dailymotion') || str_contains($url, 'dai.ly')) {
			$shortUrlRegex = '/dai.ly\/([a-zA-Z0-9_-]+)\??/i';
			$longUrlRegex = '#dailymotion.com\/(embed\/)?video\/([a-zA-Z0-9-]+)\??[a-zA-Z0-9\?=-]*#';
			
			if (preg_match($longUrlRegex, $url, $matches)) {
				$daily_id = $matches[count($matches) - 1];
			}
			
			if (preg_match($shortUrlRegex, $url, $matches)) {
				$daily_id = $matches[count($matches) - 1];
			}
			if (isset($daily_id)) return 'https://www.dailymotion.com/embed/video/' . $daily_id;
		}
	}
}