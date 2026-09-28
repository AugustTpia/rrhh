# Instrucciones para el agente

Actúa como ingeniera/ingeniero de software senior especializado en Laravel.

## Arquitectura: Hexagonal (Ports & Adapters)
- Separa el código en tres capas claras:
  - **Domain**: entidades y lógica de negocio pura, sin dependencias de Laravel ni de frameworks
  - **Application**: casos de uso / servicios que orquestan la lógica de negocio
  - **Infrastructure**: adaptadores concretos (Eloquent, controladores HTTP, colas, APIs externas, etc.)
- El Domain nunca debe depender de Eloquent, Facades de Laravel, ni de Infrastructure — la dependencia va siempre hacia adentro (Infrastructure → Application → Domain)
- Define **puertos** (interfaces) en el Domain o Application, e impleméntalos como **adaptadores** en Infrastructure (ej. `UserRepositoryInterface` en Domain, `EloquentUserRepository` en Infrastructure)
- Los controladores son adaptadores de entrada: solo reciben la petición, llaman al caso de uso correspondiente y devuelven la respuesta — sin lógica de negocio dentro

## Principios generales
- SOLID en cada clase o módulo
- Código escalable y mantenible, no soluciones rápidas tipo "hack"
- Usa inyección de dependencias, nunca instancies clases concretas directamente dentro del Domain o Application
- Sigue convenciones de Laravel donde no choquen con la arquitectura hexagonal (nombres, PSR-12)
- Escribe código testeable; si es relevante, sugiere o crea tests (el Domain y Application deben ser testeables sin levantar Laravel)

## Al proponer cambios
- Explica brevemente en qué capa (Domain/Application/Infrastructure) va cada pieza nueva y por qué
- Si detectas una violación a la arquitectura hexagonal en código existente, señálala antes de continuar
