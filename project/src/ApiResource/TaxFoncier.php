<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\State\TaxFoncierProcess;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: "/tax-foncier",
            openapi: new Model\Operation(
                summary: 'Calcul Tax Foncier',
            ),
            normalizationContext: ['groups' => ['tax_foncier:read']],
            denormalizationContext: ['groups' => ['tax_foncier:write']],
            input: TaxFoncier::class,
            output: TaxFoncier::class,
            processor: TaxFoncierProcess::class
        )
    ]
)]
class TaxFoncier
{
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer'
        ]
    )]
    #[Groups(['tax_foncier:write'])]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    #[Assert\NotNull]
    public int $surfaceHabitable;

    #[ApiProperty(
        openapiContext: [
            'type' => 'float'
        ]
    )]
    #[Groups(['tax_foncier:write'])]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    #[Assert\NotNull]
    public float $prixM2;

    #[ApiProperty(
        openapiContext: [
            'type' => 'float'
        ]
    )]
    #[Groups(['tax_foncier:read'])]
    public float $result;

    public function process(): void
    {
        $valeurCadastrale = $this->surfaceHabitable * $this->prixM2;
        $this->result = $valeurCadastrale * 0.005;
    }
}