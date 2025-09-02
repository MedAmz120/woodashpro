# Woodash Pro Button System

A comprehensive, modern button styling system designed for the Woodash Pro WordPress plugin. This system provides consistent, accessible, and visually appealing buttons across all plugin interfaces.

## Features

- **8 Button Variants**: Primary, Secondary, Success, Danger, Warning, Info, Ghost, and Outline
- **5 Size Options**: Small, Default, Large, Extra Large, and Icon-only
- **Special Effects**: Glow, Shine, Ripple, and Pulse animations
- **Loading States**: Built-in loading indicators with spinner animations
- **Button Groups**: Connected button groups for related actions
- **Responsive Design**: Mobile-first approach with touch-friendly sizing
- **Accessibility**: Focus indicators, reduced motion support, and high contrast modes
- **Font Awesome Integration**: Consistent icon usage throughout

## Quick Start

1. Include the button CSS file in your template:
```php
wp_enqueue_style('woodash-buttons', plugin_dir_url(__FILE__) . 'assets/css/buttons.css');
```

2. Use the button classes in your HTML:
```html
<button class="woodash-btn woodash-btn-primary">
    <i class="fa fa-plus"></i> Add Item
</button>
```

## Button Variants

### Primary Buttons
Use for main actions and primary call-to-actions.

```html
<button class="woodash-btn woodash-btn-primary">Primary Action</button>
```

### Secondary Buttons
Use for secondary actions that are important but not primary.

```html
<button class="woodash-btn woodash-btn-secondary">Secondary Action</button>
```

### Status Buttons
Use for actions that have specific semantic meanings.

```html
<button class="woodash-btn woodash-btn-success">Save Changes</button>
<button class="woodash-btn woodash-btn-danger">Delete Item</button>
<button class="woodash-btn woodash-btn-warning">Caution</button>
<button class="woodash-btn woodash-btn-info">Learn More</button>
```

### Ghost and Outline Buttons
Use for subtle actions or in constrained spaces.

```html
<button class="woodash-btn woodash-btn-ghost">Ghost Button</button>
<button class="woodash-btn woodash-btn-outline">Outline Button</button>
```

## Button Sizes

### Small Buttons
Use in tight spaces or for less important actions.

```html
<button class="woodash-btn woodash-btn-primary woodash-btn-sm">Small Button</button>
```

### Default Buttons
Standard size for most use cases.

```html
<button class="woodash-btn woodash-btn-primary">Default Button</button>
```

### Large Buttons
Use for important actions or on mobile devices.

```html
<button class="woodash-btn woodash-btn-primary woodash-btn-lg">Large Button</button>
```

### Extra Large Buttons
Use for hero actions or prominent call-to-actions.

```html
<button class="woodash-btn woodash-btn-primary woodash-btn-xl">Extra Large Button</button>
```

### Icon Only Buttons
Use when space is limited and the icon is self-explanatory.

```html
<button class="woodash-btn woodash-btn-primary woodash-btn-icon">
    <i class="fa fa-plus"></i>
</button>
```

## Special Effects

### Glow Effect
Adds a subtle glow around the button on hover.

```html
<button class="woodash-btn woodash-btn-primary woodash-btn-glow">Glow Button</button>
```

### Shine Effect
Creates a shine animation across the button.

```html
<button class="woodash-btn woodash-btn-primary woodash-btn-shine">Shine Button</button>
```

### Ripple Effect
Creates a ripple effect from the click point.

```html
<button class="woodash-btn woodash-btn-primary woodash-btn-ripple">Ripple Button</button>
```

### Pulse Effect
Creates a pulsing animation to draw attention.

```html
<button class="woodash-btn woodash-btn-secondary woodash-btn-pulse">Pulse Button</button>
```

## Button Groups

Group related buttons together for a connected appearance.

```html
<div class="woodash-btn-group">
    <button class="woodash-btn woodash-btn-secondary">Edit</button>
    <button class="woodash-btn woodash-btn-secondary">View</button>
    <button class="woodash-btn woodash-btn-danger">Delete</button>
</div>
```

## Loading States

Show loading feedback for async operations.

```html
<button class="woodash-btn woodash-btn-primary woodash-btn-loading" disabled>
    Loading...
</button>
```

## JavaScript Integration

### Toggle Loading State
```javascript
function toggleLoading(button) {
    button.classList.add('woodash-btn-loading');
    button.disabled = true;
    button.textContent = 'Loading...';

    // Simulate async operation
    setTimeout(() => {
        button.classList.remove('woodash-btn-loading');
        button.disabled = false;
        button.innerHTML = '<i class="fa fa-check"></i> Done';
    }, 2000);
}
```

## Accessibility Features

- **Focus Indicators**: Clear focus outlines for keyboard navigation
- **Reduced Motion**: Respects `prefers-reduced-motion` setting
- **High Contrast**: Works well in high contrast mode
- **Touch Targets**: Minimum 44px touch targets on mobile
- **Screen Readers**: Proper ARIA labels and semantic markup

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Customization

The button system uses CSS custom properties for easy theming:

```css
:root {
    --woodash-primary-color: #3b82f6;
    --woodash-secondary-color: #6b7280;
    --woodash-success-color: #10b981;
    --woodash-danger-color: #ef4444;
    --woodash-warning-color: #f59e0b;
    --woodash-info-color: #06b6d4;
    --woodash-border-radius: 8px;
    --woodash-transition-duration: 0.2s;
}
```

## Search Input Component

The Woodash Pro system also includes a modern search input component with the following features:

### Features
- **Modern Design**: Clean, rounded design with subtle shadows
- **Focus States**: Smooth green accent on focus with glow effect
- **Clear Button**: Appears when user types, allows quick clearing
- **Responsive**: Adapts to different screen sizes
- **Accessibility**: Proper focus indicators and keyboard navigation
- **Dark Mode**: Automatic dark mode support

### HTML Structure
```html
<div class="relative">
    <input type="text"
           class="woodash-search-input"
           placeholder="Search customers..."
           autocomplete="off">
    <i class="fa fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition-colors duration-200"></i>
    <button type="button"
            id="clear-search"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200 opacity-0 pointer-events-none"
            title="Clear search">
        <i class="fa fa-times"></i>
    </button>
</div>
```

### JavaScript Integration
```javascript
// Clear search functionality
function clearSearch() {
    const searchInput = document.getElementById('customer-search');
    const clearButton = document.getElementById('clear-search');

    if (searchInput) {
        searchInput.value = '';
        // Trigger search filter update
        updateSearchResults('');
    }

    // Hide clear button
    if (clearButton) {
        clearButton.classList.add('opacity-0', 'pointer-events-none');
    }
}

// Update clear button visibility
function updateClearButtonVisibility(searchTerm) {
    const clearButton = document.getElementById('clear-search');
    if (clearButton) {
        if (searchTerm.length > 0) {
            clearButton.classList.remove('opacity-0', 'pointer-events-none');
        } else {
            clearButton.classList.add('opacity-0', 'pointer-events-none');
        }
    }
}
```

### CSS Classes
- `.woodash-search-input` - Main search input styling
- Responsive breakpoints for mobile optimization
- Dark mode support with `@media (prefers-color-scheme: dark)`

## File Structure

```
WoodDash Pro/
├── assets/
│   └── css/
│       └── buttons.css          # Main button styles
├── templates/
│   ├── customers.php            # Example usage with search input
│   └── ...
├── button-showcase.html         # Interactive demo
└── README.md                    # This file
```

## Contributing

When adding new button variants or modifying existing ones:

1. Update `buttons.css` with new styles
2. Add examples to `button-showcase.html`
3. Update this README with documentation
4. Test across all supported browsers
5. Ensure accessibility compliance

## License

This button system is part of the Woodash Pro WordPress plugin and follows the same licensing terms.
