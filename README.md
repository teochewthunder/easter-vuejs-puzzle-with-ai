# Easter VueJS Puzzle with AI
This is an expansion on the [Easter VueJS Puzzle](https://github.com/teochewthunder/easter-vuejs-puzzle). The difference is that instead of providing a picture, we use Dall-E's API to generate one on the fly.

## Changes
### PHP
- The file extension has been changed. PHP is now used to call the Dall-E API endpoint to request for an image with a prompt, sent as a URL.
- The resulting URL is then inserted into the existing CSS, specifically in the CSS class `piece`.
### CSS
- The CSS class `piece` also has a `background-size` specification to ensure that it conforms with the size of the image generated.
### JavaScript
- `reset()` method
  - now accepts a parameter, `delay`.
  - this is passed on to the `startTimer()` method.
  - `message` is also changed to warn the user to wait for the image to load.
- `startTimer()` method
  - now accepts a parameter, `delay`.
  - the entire code block of the `setInterval()` function, is now encapsulated as a callback within the `setTimeout()` function, with a delay of `delay`. Within that code, `message` is changed back to "Time Elapsed".
- `refresh()` method.
  - this has been newly created. It basically reloads the page.
- `created()` method.
  - `reset()` is now run with an argument of 5000.
### HTML
- A button has been added that will run the `refresh()` method.
- The button that runs the `reset()` method, now runs it with an argument of 0.
