export function extractLabelForSelectedLanguage(label, param) {
    // Check if the label contains language codes or separators.
    if (/\w\w:/.test(label)) {
      // Define a regular expression pattern to match the desired language code and text.
      const pattern = new RegExp(`${param}:(.+?)(?:\\||$)`);

      // Execute the regular expression to find the matching content.
      const match = pattern.exec(label);

      // Check if a match was found.
      if (match) {
        // Extract and return the matched text.
        return match[1];
      }

      // Check if there are alternative languages.
      const alternatives = label.split("|");
      if (alternatives.length > 1) {
        // Return the content of the first alternative.
        const firstAlternative = alternatives[0].trim();
        // Remove any leading language code if present.
        return firstAlternative.replace(/^\w\w:/, "").trim();
      }
    }

    // If there's no language codes or matches, return the label as is.
    return label;
  }