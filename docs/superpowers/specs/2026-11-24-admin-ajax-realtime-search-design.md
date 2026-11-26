# Admin AJAX CRUD and Realtime Search

## Intent

Make admin CRUD interactions and searches responsive without full-page reloads. Realtime search covers every admin list that currently has a search field: users, symptoms, diseases, samples, articles, and decision-tree rules.

## Goals

- Send CRUD form submissions through AJAX and refresh the visible list after success without reloading the document.
- Search as the user types, using the existing server-side filters and pagination.
- Update matching rows and pagination controls without a full-page navigation.
- Keep current validation feedback, CSRF refresh, and normal non-AJAX controller fallbacks.

## Non-goals and constraints

- Do not change search fields, matching rules, page size, or unrelated public/user flows.
- Do not fetch every record into the browser; existing lists are server-paginated.
- Preserve the current CodeIgniter, Bootstrap/jQuery, and controller/model patterns.

## Approaches

- **AJAX server-side (chosen):** debounce input and request the existing filtered/paginated results; replace only list content and pager. This preserves current pagination and avoids transferring all records.
- **Browser-side filtering:** load all rows and filter/paginate in JavaScript. Rejected because it changes the current server-pagination model and scales poorly as records grow.

## Implementation calls

Use the existing list endpoints and search queries, adding an AJAX response path for list fragments. Keep pagination usable without full-page reloads. After a successful CRUD request, refresh the current list fragment so creates, edits, and deletes stay consistent with the active search and page. Do not remove the existing non-AJAX redirects.

## Validation

Add focused tests for AJAX list responses and live-search behavior across all six pages, and run the relevant existing PHP test suite.
