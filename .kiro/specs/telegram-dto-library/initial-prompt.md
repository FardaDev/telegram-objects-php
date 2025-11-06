You are an autonomous AI developer. Your task is to build a **framework-agnostic PHP library** that provides **typed Telegram Bot API DTOs and base objects**, extracted and generalized from the open-source project [`defstudio/telegraph`](https://github.com/defstudio/telegraph). The result should be a reusable, well-structured PHP package that others can depend on when building Telegram bot frameworks or integrations.

### Project Goals

- Create a standalone PHP package (no Laravel or other framework dependency).

- Contain all Telegram object and DTO definitions (Chat, Message, User, Update, etc.).

- Serve as a clean, strongly-typed data layer on top of the Telegram Bot API.

- Include mechanisms to track and stay in sync with upstream `defstudio/telegraph` updates.

---

### Steps

1. **Initialize Project Structure**

   - Create a standard PHP package scaffold:

     ```

     /src

     /tests

     /docs

     /examples

     composer.json

     README.md

     LICENSE

     .gitignore

     phpunit.xml

     .editorconfig

     ```

   - Set PHP requirement to >=8.2.

   - Include `phpstan` and `phpunit` setup.

   - License: MIT.

2. **Clone Upstream Reference (Telegraph)**

   - Clone `https://github.com/defstudio/telegraph` into a temporary or ignored folder, e.g. `/vendor_sources/telegraph/`.

   - Add this path to `.gitignore` so it is not committed.

3. **Extract and Generalize DTO Layer**

   - From the Telegraph source, copy or refactor the DTOs (in `src/DTO/`) and other pure data objects that model Telegram entities.

   - Remove any Laravel or framework-specific dependencies.

   - Convert all Laravel-specific helpers (e.g., `Arrayable`) to PSR interfaces or custom lightweight equivalents.

   - Ensure all objects:

     - Use strict typing (`declare(strict_types=1)`).

     - Contain PHPDoc and type hints for IDEs.

     - Have static `fromArray()` and `toArray()` methods.

     - Avoid all magic methods (`__get`, `__set`, `__call`).

   - Structure namespaces as `Telegram\Objects\DTO\*`.

4. **Version Tracking Mechanism**

   - Implement a simple tracking file (e.g. `/upstream.json`) containing:

     ```json

     {

       "source": "defstudio/telegraph",

       "last_commit": "<commit_hash>",

       "last_checked": "<date>"

     }

     ```

   - Include a CLI script (`/scripts/check-upstream.php`) that compares the latest Telegraph commit with the stored one, and optionally generates a diff report of changed DTOs.

5. **Documentation and Examples**

   - Create `README.md` describing usage, purpose, and how to import DTOs.

   - Add `docs/contributing.md` and `docs/upstream-sync.md` explaining how to update from Telegraph.

   - Include example scripts in `/examples` showing:

     - Creating and serializing a `Chat` object.

     - Parsing an update payload into DTOs.

6. **Publish**

   - Initialize a new git repository.

   - Preserve MIT license and include attribution notice to `DefStudio/Telegraph` for DTO source inspiration.

   - Push to GitHub under your namespace (e.g., `yourname/telegram-dto`).

   - Prepare for Packagist publication with `composer.json` metadata.

7. write test for all DTOs and other objects in the project and after creating each run tests so you know it works, you can review and use the the test in telegraph lib in tests folder is already has test for moest things it will help you write better tests
---

### Deliverables

- A fully bootstrapped, framework-agnostic PHP library containing Telegram Bot API DTOs.

- Source code organized, documented, and type-safe.

- Upstream tracking mechanism working.

- Ready for public release and Packagist installation.