# HM Primary Category Block

Displays the primary category for a post in the WordPress block editor.

## Features

- Displays the primary category for a post
- Supports Yoast SEO primary category selection
- Server-side rendered for optimal performance

## Usage

The Primary Category block is automatically available after plugin activation. The block uses the `postId` context and is typically used within:

- Query Loop blocks
- Post Templates
- Single post layouts

### How it works

1. The block automatically detects the post ID from context
2. If Yoast SEO is active, it uses the primary category set by Yoast
3. For posts with multiple categories, it returns the top-level parent category
4. For single category posts, it returns that category

### Block Name

The block is registered as `hm/primary-category` and can be inserted programmatically or used in block patterns.

### Customization

The block outputs a link with the class `hentry__category` which can be styled via CSS:

```css
.hentry__category {
    /* Your custom styles */
}
```

## Installation

1. Clone this repository to your WordPress plugins directory
2. Run `npm install` to install dependencies
3. Run `npm run build` to build the block
4. Activate the plugin in WordPress

## Development

- `npm start` - Start development mode with hot reload
- `npm run build` - Build production files
- `npm run lint` - Run linters

## Requirements

- WordPress 6.0+
- PHP 8.2+
- Node.js 22+

## License

GPL-2.0-or-later
