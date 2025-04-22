# One Podcast Feed Manager

A custom WordPress plugin that generates an RSS feed for podcast episodes based on posts in the `podcast` category. Fully compatible with iTunes/Apple Podcasts specifications.

## 📦 Features

- Custom podcast RSS feed available at: `/feed/podcast`
- Uses posts from the `podcast` category as episodes
- Supports episode metadata:
  - `episode_cover`
  - `episode_mp3_url` or `anchor_mp3_url`
  - `episode_duration`
  - `episode_number`
  - `episode_season`
  - `episode_type`
- Custom settings page to manage:
  - Default podcast cover
  - Author name
  - Owner name & email
  - Explicit content flag

## 🚀 Installation

1. Download or clone this repository.
2. Place the plugin folder inside your WordPress `wp-content/plugins` directory.
3. Activate the plugin via the WordPress admin dashboard (`Plugins` > `Installed Plugins`).

## ⚙️ Configuration

After activation:

1. Go to **Podcast Feed** in the WordPress admin sidebar.
2. Fill in the required podcast settings:
   - Default cover URL (used when no `episode_cover` is set)
   - Author name (displayed in `<itunes:author>`)
   - Owner name and email (required by Apple)
   - Explicit flag (`true` or `false`)

## 📝 Custom Fields

The following custom fields are used per post:

| Field Name         | Description                                  |
|--------------------|----------------------------------------------|
| `episode_cover`    | Custom episode image URL                     |
| `episode_mp3_url`  | Primary audio file URL (preferred)           |
| `anchor_mp3_url`   | Fallback audio file URL                      |
| `episode_duration` | Duration in HH:MM:SS format                  |
| `episode_number`   | iTunes episode number                        |
| `episode_season`   | iTunes season number                         |
| `episode_type`     | `full`, `trailer`, or `bonus` (default: full)|

## 📡 RSS Feed

Once the plugin is active, your podcast feed will be available at:

```
https://yourdomain.com/feed/podcast
```

You can submit this URL to podcast platforms like Apple Podcasts, Spotify, etc.

## 💡 Notes

- Make sure your posts are categorized as `podcast`.
- Avoid using `<iframe>` tags in post content. These will be stripped from the feed automatically.

## 📧 Support

If you encounter issues, feel free to open an issue or reach out via your preferred contact method.
