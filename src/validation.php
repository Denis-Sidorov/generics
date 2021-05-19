<?php declare(strict_types = 1);

/**
 * @template T
 */
class ValidationResult
{
    /** @var T|null $result */
    private $result;

    /**
     * @param T $result
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * @template T2
     * @param T2 $result
     * @return ValidationResult<T2>
     */
    public static function VALID($result)
    {
        return new ValidationResult($result);
    }

    /**
     * @return ValidationResult<null>
     */
    public static function INVALID()
    {
        return new ValidationResult(null);
    }

    /**
     * @return T|null
     */
    public function getResult()
    {
        return $this->result;
    }
}

class MarkResultDto {
    private string $name;
    private string $description;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}


class MarkRejectBoxValidator
{
    /**
     *	@return ValidationResult<MarkResultDto>
     */
    public function validate(): ValidationResult
    {
        $dto = new MarkResultDto('test name', 'test description');
        $test = new ValidationResult(null);
        // phpstan: Method MarkRejectBoxValidator::validate() should return ValidationResult<MarkResultDto> but returns ValidationResult<null>.
        return $test;
    }

    /**
     *	@return ValidationResult<MarkResultDto>
     */
    public function validate2(): ValidationResult
    {
        $dto = new MarkResultDto('test name', 'test description');
        $test = ValidationResult::VALID(null);
        // phpstan: Method MarkRejectBoxValidator::validate2() should return ValidationResult<MarkResultDto> but returns ValidationResult<null>.
        return $test;
    }

    /**
     *	@return ValidationResult<MarkResultDto>
     */
    public function validate3(): ValidationResult
    {
        $dto = new MarkResultDto('test name', 'test description');
        $test = ValidationResult::INVALID();
        // phpstan: Method MarkRejectBoxValidator::validate3() should return ValidationResult<MarkResultDto> but returns ValidationResult<null>.
        return $test;
    }
}

class ValidatorUser
{
    private MarkRejectBoxValidator $validator;

    public function __construct(MarkRejectBoxValidator $validator)
    {
        $this->validator = $validator;
    }

    public function run(): void
    {
        $result = $this->validator->validate();
        $test = $result->getResult() !== null ? $result->getResult()->getName() : 'Jon Dhoe';
    }
}
