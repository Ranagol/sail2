# 🚀 Laravel AI Instructions (Boost-Optimized)

## 🧠 SYSTEM ROLE

You are generating code for a Laravel application that follows a **strict Service + DTO architecture**.

You MUST follow all rules defined in this document.
If a rule is violated, the output is considered **incorrect**.

---

## ⚙️ CORE ARCHITECTURE RULES (MANDATORY)

1. Controllers MUST be thin (no business logic)
2. Business logic MUST exist ONLY in Service classes
3. ALL input data MUST be passed using DTOs
4. Validation MUST be handled via Form Request classes
5. Services MUST NOT depend on Request objects
6. Dependency Injection MUST be used (no facades in Services)
7. All classes MUST have a single responsibility

---

## 📁 STRICT FOLDER STRUCTURE

All files MUST be placed in the following directories:

* Controllers → `app/Http/Controllers/`
* Requests → `app/Http/Requests/`
* DTOs → `app/DTOs/`
* Services → `app/Services/`
* Actions → `app/Actions/` (for complex workflows)
* Models → `app/Models/`

Do NOT create alternative folder structures.

---

## 📛 NAMING CONVENTIONS (MANDATORY)

* Controllers: `{Entity}Controller`
* Services: `{Entity}Service`
* DTOs: `{Action}{Entity}Data`
* Requests: `{Action}{Entity}Request`
* Actions: `{Action}{Entity}Action`

### Examples:

* `UserController`
* `CreateUserData`
* `StoreUserRequest`
* `UserService`

---

## 🔄 REQUIRED GENERATION PATTERN

When generating a feature, you MUST follow this exact flow:

Request → DTO → Service → Model → Response

---

## 🧪 GENERATION RECIPES

### ✅ Recipe: Create Endpoint

When asked to create a new endpoint, ALWAYS generate:

1. Form Request (validation)
2. DTO (data transfer object)
3. Service (business logic)
4. Controller method

---

### ✅ Recipe: Update Endpoint

Generate:

1. Update Request
2. Update DTO
3. Service method
4. Controller method

---

### ✅ Recipe: Delete Endpoint

Generate:

1. Service method
2. Controller method

---

## 📦 DTO RULES (STRICT)

* DTOs MUST be immutable
* Use `public readonly` properties
* MUST include a `fromRequest()` factory method
* MUST NOT contain business logic

### ✅ Example

```php
class CreateUserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromRequest(StoreUserRequest $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }
}
```

---

## ⚙️ SERVICE RULES (STRICT)

* ALL business logic MUST live here
* MUST be reusable
* MUST be testable
* MUST use dependency injection
* MUST define return types

### ✅ Example

```php
class UserService
{
    public function createUser(CreateUserData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => bcrypt($data->password),
        ]);
    }
}
```

---

## 🎯 CONTROLLER RULES (STRICT)

* MUST NOT contain business logic
* MUST ONLY:

  * accept Request
  * convert to DTO
  * call Service
  * return response

### ✅ Example

```php
class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function store(StoreUserRequest $request)
    {
        $dto = CreateUserData::fromRequest($request);

        $user = $this->userService->createUser($dto);

        return response()->json($user);
    }
}
```

---

## 🚫 FORBIDDEN PATTERNS (CRITICAL)

The following MUST NEVER be generated:

* Business logic inside Controllers
* Accessing Request inside Services
* Static model calls inside Controllers
* Mixing validation with business logic
* Large “God” classes with multiple responsibilities

If encountered → REFACTOR immediately.

---

## 🧱 ADVANCED RULES

### When to Use Actions

Use Actions for:

* Multi-step workflows
* Complex domain logic
* Cross-service orchestration

---

### When to Introduce Repositories

Use Repositories ONLY when:

* Queries become complex
* Multiple data sources are involved

---

## 🧪 TESTABILITY REQUIREMENTS

* Services MUST be unit-testable
* Avoid Laravel facades in core logic
* Prefer constructor injection

---

## 🎨 CODE STYLE

* Use typed properties
* Always declare return types
* Follow PSR-12
* Use constructor property promotion where possible

---

## 📌 FINAL RULE

If uncertain:

1. Follow the examples in this document
2. Prefer consistency over creativity
3. NEVER simplify by breaking architecture rules

---

## 🤖 AI EXECUTION DIRECTIVE

You MUST:

* Follow ALL rules exactly
* Generate COMPLETE implementations (not partial)
* Respect naming, structure, and flow
* Default to the defined architecture in ALL cases

Failure to follow these rules = incorrect output.


1. General Principles
Tests first: Always generate tests before implementation (TDD approach).
Behavior-driven: Focus on what the code should do, not how it does it.
Readable & maintainable: Use descriptive method names, proper spacing, and comments where necessary.
High coverage: Include happy path, edge cases, failure scenarios, and exception handling.
2. Test Structure
Use Arrange-Act-Assert (AAA) pattern for all tests.
Separate tests by behavior:
Happy path
Input validation errors
Exceptions / error handling
Edge cases
Use data providers for repetitive tests with multiple inputs.
Avoid testing implementation details unless strictly necessary.
3. Naming Conventions

Use descriptive test method names:

test_createUser_withInvalidEmail_throwsException
test_updateProduct_withNegativePrice_returnsError
test_calculateDiscount_withMultipleProducts_appliesCorrectDiscount
Be consistent with camelCase or snake_case.
4. Assertions
Prefer specific assertions (assertEquals, assertTrue, assertFalse, assertInstanceOf, expectException) over generic assertions.
Always assert expected results, not just that the code runs.

For exceptions:

$this->expectException(InvalidArgumentException::class);
$this->expectExceptionMessage('Invalid email address');
5. Edge Cases & Validation
Always test invalid input, nulls, empty strings, negative numbers, and unexpected types.
Test boundary values (max/min length, max/min numeric values).
Include tests for duplicate entries, missing required fields, and conflicting states.
6. Test Independence
Each test should be self-contained.
Avoid shared mutable state unless properly reset in setUp() or tearDown().
7. Implementation Rules for AI
Do not generate implementation unless explicitly instructed.
If implementing, generate minimal code that passes all tests, avoid over-engineering.
Include comments for complex logic where necessary.
8. Formatting Rules
Include type hints and return types if using PHP 7+.
9. Example Prompt for AI
Generate PHPUnit tests for a UserService class. Follow TDD:
- Write tests first; do not implement yet.
- Cover:
  - valid user creation
  - invalid email
  - duplicate email
  - missing required fields
- Include edge cases and exceptions
- Use data providers where appropriate
- Follow AAA pattern
- Use descriptive test names
