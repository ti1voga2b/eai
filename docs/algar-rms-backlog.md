# Backlog do conector Algar RMS

Este arquivo acompanha o escopo da integração Algar/RMS sem tornar o modelo do EAI dependente deste provedor. Cada linha é uma rota/variante publicada nas fontes oficiais indicadas. Rotas citadas somente em guias são marcadas como tal; confirmar o contrato vigente antes de implementá-las.

**Legenda de status**

- **Implementado — teste simulado:** código do cliente EAI e teste automatizado com HTTP fake; não significa validação no Sandbox.
- **Backlog — não implementado / não testado:** fora da primeira etapa.
- **Guia — não implementado / contrato a confirmar:** a fonte é um guia funcional, sem página OpenAPI individual no índice consultado.

A primeira entrega implementa o serviço interno de acesso ao provedor. Ela **não** cria ainda uma API pública do EAI para o site da empresa. A fachada pública, autenticação das aplicações consumidoras, autorização e contratos canônicos ficam para etapa própria.

## Autenticação e assinantes

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Implementado — teste simulado | POST | `/auth/token` | [Gera token de acesso](https://telecom-enabler.readme.io/reference/authtoken.md) |
| Implementado — teste simulado | GET | `/v2/subscribers` | [Lista assinantes](https://telecom-enabler.readme.io/reference/subscriberlist.md) |
| Backlog — não implementado / não testado | POST | `/v2/subscribers` | [Novo assinante](https://telecom-enabler.readme.io/reference/subscribercreate.md) |
| Backlog — não implementado / não testado | GET | `/v2/subscribers/{id}` | [Busca assinante pelo id](https://telecom-enabler.readme.io/reference/subscriberget.md) |
| Backlog — não implementado / não testado | GET | `/v2/subscribers/by/document/{document}` | [Busca assinante pelo documento](https://telecom-enabler.readme.io/reference/subscribergetbydocument.md) |
| Backlog — não implementado / não testado | GET | `/v2/subscribers/by/ref/{ref}` | [Busca assinante pelo ID externo](https://telecom-enabler.readme.io/reference/subscribergetbyref.md) |
| Backlog — não implementado / não testado | GET | `/v2/products/available` | [Lista produtos disponíveis](https://telecom-enabler.readme.io/reference/productlistavailable.md) |

## Serviços, produtos e perfis

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Backlog — não implementado / não testado | POST | `/v2/services` | [Novo serviço](https://telecom-enabler.readme.io/reference/servicecreate.md) |
| Backlog — não implementado / não testado | GET | `/v2/services` | [Lista serviços](https://telecom-enabler.readme.io/reference/servicelist.md) |
| Backlog — não implementado / não testado | GET | `/v2/services/{id}` | [Busca serviço pelo id](https://telecom-enabler.readme.io/reference/serviceget.md) |
| Backlog — não implementado / não testado | GET | `/v2/services/by/ref/{ref}` | [Busca serviço pelo ref](https://telecom-enabler.readme.io/reference/servicegetbyref.md) |
| Backlog — não implementado / não testado | GET | `/v2/services/by/terminal/{terminal}` | [Busca serviço pelo terminal](https://telecom-enabler.readme.io/reference/servicegetbyterminal.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/services/{id}/subscriber` | [Altera assinante do serviço](https://telecom-enabler.readme.io/reference/serviceupdatesubscriber.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/services/{id}/description` | [Altera descrição do serviço](https://telecom-enabler.readme.io/reference/serviceupdatedescription.md) |
| Backlog — não implementado / não testado | POST | `/v2/services/{id}/cancel` | [Cancela serviço](https://telecom-enabler.readme.io/reference/servicecancel.md) |
| Backlog — não implementado / não testado | GET | `/v2/profiles` | [Lista perfis](https://telecom-enabler.readme.io/reference/profilelist.md) |
| Backlog — não implementado / não testado | POST | `/v2/services/{id}/products` | [Adiciona produto ao serviço](https://telecom-enabler.readme.io/reference/serviceaddproduct.md) |
| Backlog — não implementado / não testado | POST | `/v2/services/{id}/products/{product}/cancel` | [Remove produto do serviço](https://telecom-enabler.readme.io/reference/servicecancelproduct.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/services/{id}/products/main` | [Altera produto principal do serviço](https://telecom-enabler.readme.io/reference/serviceswitchproduct.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/mobilelines/{id}/restriction` | [Define restrição de uso](https://telecom-enabler.readme.io/reference/serviceupdaterestriction.md) |
| Backlog — não implementado / não testado | POST | `/v2/services/{id}/block` | [Bloqueia serviço](https://telecom-enabler.readme.io/reference/serviceblock.md) |
| Backlog — não implementado / não testado | POST | `/v2/services/{id}/unblock` | [Desbloqueia serviço](https://telecom-enabler.readme.io/reference/serviceunblock.md) |
| Backlog — não implementado / não testado | POST | `/v2/services/{id}/suspend` | [Inicia suspensão](https://telecom-enabler.readme.io/reference/servicestartsuspension.md) |
| Backlog — não implementado / não testado | POST | `/v2/services/{id}/unsuspend` | [Finaliza suspensão](https://telecom-enabler.readme.io/reference/serviceendsuspension.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/services/{id}/cycle` | [Altera ciclo do serviço](https://telecom-enabler.readme.io/reference/serviceupdatecycle.md) |

## Numeração e portabilidade

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Backlog — não implementado / não testado | GET | `/v2/tns` | [Lista numeração](https://telecom-enabler.readme.io/reference/tnlist.md) |
| Backlog — não implementado / não testado | GET | `/v2/tns/available` | [Lista numeração disponível](https://telecom-enabler.readme.io/reference/tnlistavailable.md) |
| Backlog — não implementado / não testado | GET | `/v2/tns/{tn}` | [Exibe numeração em detalhe](https://telecom-enabler.readme.io/reference/tnget.md) |
| Backlog — não implementado / não testado | GET | `/v2/tns/available/locales` | [Lista localidades com numeração disponível](https://telecom-enabler.readme.io/reference/tnlistlocales.md) |
| Backlog — não implementado / não testado | GET | `/v2/locales/coverage` | [Lista localidades dentro da cobertura](https://telecom-enabler.readme.io/reference/tnlistcoverage.md) |
| Backlog — não implementado / não testado | GET | `/v2/tns/usage` | [Estatísticas de uso da numeração](https://telecom-enabler.readme.io/reference/tngetusage.md) |
| Backlog — não implementado / não testado | GET | `/v2/tns/lnp/requests/by/tn/{tn}` | [Portabilidade por TN](https://telecom-enabler.readme.io/reference/tngetportabilitybytn.md) |
| Backlog — não implementado / não testado | GET | `/v2/tns/lnp/requests/by/bp/{bp}` | [Portabilidade por BP](https://telecom-enabler.readme.io/reference/tngetportabilitybybp.md) |
| Backlog — não implementado / não testado | GET | `/v2/tns/lnp/processing` | [Lista portabilidades em atualização](https://telecom-enabler.readme.io/reference/tnlistportabilities.md) |
| Backlog — não implementado / não testado | GET | `/v2/tns/lnp/windows` | [Lista janelas de portabilidade](https://telecom-enabler.readme.io/reference/tngetlnpwindows.md) |
| Backlog — não implementado / não testado | POST | `/v2/tns/requests` | [Solicita nova numeração](https://telecom-enabler.readme.io/reference/tncreaterequest.md) |
| Backlog — não implementado / não testado | GET | `/v2/tns/requests` | [Lista solicitações de numeração](https://telecom-enabler.readme.io/reference/tngetrequests.md) |
| Backlog — não implementado / não testado | GET | `/v2/tns/requests/{id}` | [Busca solicitação de numeração](https://telecom-enabler.readme.io/reference/tngetrequest.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/{id}/lnp` | [Solicita portabilidade móvel](https://telecom-enabler.readme.io/reference/mobilelinerequestportability.md) |
| Guia — não implementado / contrato a confirmar | POST | `/v2/landlines/{id}/lnp` | [Guia de portabilidade](https://telecom-enabler.readme.io/docs/portability.md) |
| Guia — não implementado / contrato a confirmar | GET | `/v2/tns/lnp/requests/by/ref/{ref}` | [Guia de portabilidade](https://telecom-enabler.readme.io/docs/portability.md) |
| Guia — não implementado / contrato a confirmar | GET | `/v2/{mobilelines|landlines}/{id}/lnp` | [Guia de portabilidade](https://telecom-enabler.readme.io/docs/portability.md) |
| Guia — não implementado / contrato a confirmar | GET | `/v2/{mobilelines|landlines}/{id}/lnp/{lnp}` | [Guia de portabilidade](https://telecom-enabler.readme.io/docs/portability.md) |
| Guia — não implementado / contrato a confirmar | GET | `/v2/{mobilelines|landlines}/{id}/lnp/current` | [Guia de portabilidade](https://telecom-enabler.readme.io/docs/portability.md) |
| Guia — não implementado / contrato a confirmar | PATCH | `/v2/{mobilelines|landlines}/{id}/lnp/{lnp}/window` | [Guia de portabilidade](https://telecom-enabler.readme.io/docs/portability.md) |
| Guia — não implementado / contrato a confirmar | POST | `/v2/{mobilelines|landlines}/{id}/lnp/{lnp}/cancel` | [Guia de portabilidade](https://telecom-enabler.readme.io/docs/portability.md) |

## Linhas móveis

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Backlog — não implementado / não testado | POST | `/v2/mobilelines` | [Cria linha móvel](https://telecom-enabler.readme.io/reference/mobilelinecreate.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/activate` | [Ativa linha móvel](https://telecom-enabler.readme.io/reference/mobilelineactivate.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobilelines` | [Lista linhas móveis](https://telecom-enabler.readme.io/reference/mobilelinelist.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobilelines/{id}` | [Busca linha móvel](https://telecom-enabler.readme.io/reference/mobilelineget.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobilelines/by/ref/{ref}` | [Busca linha pelo ID externo](https://telecom-enabler.readme.io/reference/mobilelinegetbyref.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobilelines/by/terminal/{terminal}` | [Busca linha pelo terminal](https://telecom-enabler.readme.io/reference/mobilelinegetbyterminal.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobilelines/by/tn/{tn}` | [Busca linha pela numeração](https://telecom-enabler.readme.io/reference/mobilelinegetbytn.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/{id}/cancel` | [Cancela linha móvel](https://telecom-enabler.readme.io/reference/mobilelinecancel.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/mobilelines/{id}/simcard` | [Altera SIM card](https://telecom-enabler.readme.io/reference/mobilelineupdatesimcard.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobilelines/{id}/lnp/{lnp}` | [Busca portabilidade da linha](https://telecom-enabler.readme.io/reference/mobilelinegetportability.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/mobilelines/{id}/lnp/{lnp}/window` | [Altera janela de portabilidade](https://telecom-enabler.readme.io/reference/mobilelineupdatewindow.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/{id}/lnp/{lnp}/cancel` | [Cancela portabilidade](https://telecom-enabler.readme.io/reference/mobilelinecancelportability.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/mobilelines/{id}/description` | [Altera descrição da linha](https://telecom-enabler.readme.io/reference/mobilelineupdatedescription.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/{id}/products` | [Adiciona produto](https://telecom-enabler.readme.io/reference/mobilelineaddproduct.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/mobilelines/{id}/products/main` | [Altera produto principal](https://telecom-enabler.readme.io/reference/mobilelineswitchproduct.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobilelines/{id}/usage/data` | [Consulta consumo de dados](https://telecom-enabler.readme.io/reference/mobilelinegetusage.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/{id}/sms/send` | [Envia SMS](https://telecom-enabler.readme.io/reference/mobilelinesendsms.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/{id}/products/{product}/cancel` | [Remove produto](https://telecom-enabler.readme.io/reference/mobilelinecancelproduct.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/{id}/block` | [Bloqueia linha móvel](https://telecom-enabler.readme.io/reference/mobilelineblock.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/{id}/unblock` | [Desbloqueia linha móvel](https://telecom-enabler.readme.io/reference/mobilelineunblock.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/{id}/suspend` | [Inicia suspensão temporária](https://telecom-enabler.readme.io/reference/mobilelinestartsuspension.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobilelines/{id}/unsuspend` | [Finaliza suspensão temporária](https://telecom-enabler.readme.io/reference/mobilelineendsuspension.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobilelines/{id}/device` | [Consulta dispositivo](https://telecom-enabler.readme.io/reference/mobilelinegetdevice.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobilelines/{id}/state` | [Consulta estado](https://telecom-enabler.readme.io/reference/mobilelinegetstate.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/mobilelines/{id}/cycle` | [Altera ciclo do plano](https://telecom-enabler.readme.io/reference/mobilelineupdatecycle.md) |

## Troncos móveis

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Backlog — não implementado / não testado | GET | `/v2/mobiletrunks` | [Lista troncos](https://telecom-enabler.readme.io/reference/mobiletrunklist.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobiletrunks` | [Cria tronco](https://telecom-enabler.readme.io/reference/mobiletrunkcreate.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobiletrunks/{id}` | [Busca tronco](https://telecom-enabler.readme.io/reference/mobiletrunkget.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobiletrunks/{id}/cancel` | [Cancela tronco](https://telecom-enabler.readme.io/reference/mobiletrunkcancel.md) |
| Backlog — não implementado / não testado | GET | `/v2/mobiletrunks/{id}/tns` | [Lista numeração do tronco](https://telecom-enabler.readme.io/reference/mobiletrunklisttns.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobiletrunks/{id}/tns` | [Adiciona numeração ao tronco](https://telecom-enabler.readme.io/reference/mobiletrunkcreatetn.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobiletrunks/{id}/tns/by/quantity` | [Adiciona numeração por quantidade](https://telecom-enabler.readme.io/reference/mobiletrunkcreatetnbyquantity.md) |
| Backlog — não implementado / não testado | POST | `/v2/mobiletrunks/{id}/tns/cancel` | [Remove numeração do tronco](https://telecom-enabler.readme.io/reference/mobiletrunkcanceltn.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/mobiletrunks/{id}/connection` | [Atualiza conexão do tronco](https://telecom-enabler.readme.io/reference/mobiletrunkupdateconnection.md) |

## SIM Cards e eSIM

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Backlog — não implementado / não testado | GET | `/v2/simcards` | [Lista SIM Cards](https://telecom-enabler.readme.io/reference/simcardlist.md) |
| Backlog — não implementado / não testado | GET | `/v2/simcards/available` | [Lista SIM Cards disponíveis](https://telecom-enabler.readme.io/reference/simcardlistavailable.md) |
| Backlog — não implementado / não testado | GET | `/v2/simcards/summary` | [Resumo de SIM Cards](https://telecom-enabler.readme.io/reference/simcardsummary.md) |
| Backlog — não implementado / não testado | GET | `/v2/simcards/requests` | [Lista pedidos de SIM Cards](https://telecom-enabler.readme.io/reference/simcardgetrequests.md) |
| Backlog — não implementado / não testado | GET | `/v2/simcards/requests/delivery/last` | [Último endereço de entrega](https://telecom-enabler.readme.io/reference/simcardgetlastrequestdelivery.md) |
| Backlog — não implementado / não testado | POST | `/v2/simcards/requests` | [Cria pedido de SIM Cards](https://telecom-enabler.readme.io/reference/simcardcreaterequest.md) |
| Backlog — não implementado / não testado | GET | `/v2/simcards/requests/{id}` | [Busca pedido de SIM Cards](https://telecom-enabler.readme.io/reference/simcardgetrequest.md) |
| Backlog — não implementado / não testado | POST | `/v2/simcards/requests/{id}/update` | [Comenta pedido de SIM Cards](https://telecom-enabler.readme.io/reference/simcardaddrequestupdate.md) |
| Backlog — não implementado / não testado | POST | `/v2/simcards/requests/{id}/cancel` | [Cancela pedido de SIM Cards](https://telecom-enabler.readme.io/reference/simcardcancelrequest.md) |
| Backlog — não implementado / não testado | GET | `/v2/simcards/requests/{id}/simcards` | [Lista SIM Cards do pedido](https://telecom-enabler.readme.io/reference/simcardlistrequestsimcards.md) |
| Backlog — não implementado / não testado | GET | `/v2/simcards/requests/{id}/attachments/{attachmentId}` | [Baixa anexo do pedido](https://telecom-enabler.readme.io/reference/simcardgetrequestattachment.md) |

## Gateways, cobertura e estatísticas

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Backlog — não implementado / não testado | GET | `/v2/gateways` | [Lista gateways](https://telecom-enabler.readme.io/reference/gatewaylist.md) |
| Backlog — não implementado / não testado | GET | `/v2/locales/by/zip_code/{zip_code}` | [Consulta localidade pelo CEP](https://telecom-enabler.readme.io/reference/localegetbyzipcode.md) |
| Backlog — não implementado / não testado | GET | `/v2/stats` | [Estatística](https://telecom-enabler.readme.io/reference/statsget.md) |

## Webhooks e notificações

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Backlog — não implementado / não testado | GET | `/v2/webhooks` | [Consulta webhooks](https://telecom-enabler.readme.io/reference/webhookget.md) |
| Backlog — não implementado / não testado | POST | `/v2/webhooks` | [Configura webhook](https://telecom-enabler.readme.io/reference/webhookset.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/webhooks/{id}` | [Altera webhook](https://telecom-enabler.readme.io/reference/webhookupdate.md) |
| Backlog — não implementado / não testado | DELETE | `/v2/webhooks/{id}` | [Remove webhook](https://telecom-enabler.readme.io/reference/webhookdelete.md) |
| Backlog — não implementado / não testado | POST | `/v2/webhooks/{id}/test` | [Dispara evento de teste](https://telecom-enabler.readme.io/reference/webhooktest.md) |

## CDR e dados de uso

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Backlog — não implementado / não testado | GET | `/v2/cdr` | [Lista arquivos CDR](https://telecom-enabler.readme.io/reference/cdrlist.md) |
| Backlog — não implementado / não testado | GET | `/v2/cdr/settings` | [Consulta configuração CDR](https://telecom-enabler.readme.io/reference/cdrsettingsget.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/cdr/settings` | [Altera configuração CDR](https://telecom-enabler.readme.io/reference/cdrsettingsset.md) |

## Faturamento

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Backlog — não implementado / não testado | GET | `/v2/billing/bundles` | [Lista lotes fiscais](https://telecom-enabler.readme.io/reference/invoicebundlelist.md) |
| Backlog — não implementado / não testado | GET | `/v2/billing/collections` | [Lista fechamentos](https://telecom-enabler.readme.io/reference/invoicecollectionlist.md) |

## Protocolos e atendimento

| Status | Método | Caminho | Fonte oficial |
|---|---|---|---|
| Backlog — não implementado / não testado | GET | `/v2/protocols` | [Lista protocolos](https://telecom-enabler.readme.io/reference/protocollist.md) |
| Backlog — não implementado / não testado | POST | `/v2/protocols` | [Novo protocolo](https://telecom-enabler.readme.io/reference/protocolcreate.md) |
| Backlog — não implementado / não testado | GET | `/v2/protocols/{id}` | [Busca protocolo](https://telecom-enabler.readme.io/reference/protocolget.md) |
| Backlog — não implementado / não testado | POST | `/v2/protocols/{id}/update` | [Atualiza protocolo](https://telecom-enabler.readme.io/reference/protocolupdate.md) |
| Backlog — não implementado / não testado | GET | `/v2/protocols/categories` | [Lista categorias](https://telecom-enabler.readme.io/reference/protocolcategorieslist.md) |
| Backlog — não implementado / não testado | PATCH | `/v2/protocols/{id}/category` | [Atualiza categoria](https://telecom-enabler.readme.io/reference/protocolupdatecategory.md) |
| Backlog — não implementado / não testado | POST | `/v2/protocols/{id}/close` | [Fecha protocolo](https://telecom-enabler.readme.io/reference/protocolclose.md) |
| Backlog — não implementado / não testado | POST | `/v2/protocols/{id}/escalate` | [Escalona protocolo](https://telecom-enabler.readme.io/reference/protocolescalate.md) |
| Backlog — não implementado / não testado | POST | `/v2/protocols/{id}/action` | [Executa ação](https://telecom-enabler.readme.io/reference/protocolaction.md) |

## Observações de implementação

1. O cliente usa `ALGAR_RMS_BASE_URL`, `ALGAR_RMS_CLIENT_ID` e `ALGAR_RMS_CLIENT_SECRET`; não há host padrão. Para esta entrega, configure exclusivamente a URL Sandbox fornecida no credenciamento.
2. O cache de token é separado por host e credenciais, e a chave usa fingerprint SHA-256. O segredo bruto não é escrito em código, logs ou chave do cache.
3. A listagem de assinantes aceita `page`, `size` e `q` e valida o envelope `data/meta`, incluindo `currentPage`, `pageSize`, `totalItems` e `totalPages`.
4. Testes usam respostas HTTP simuladas. Sandbox não foi validado porque o host real e as credenciais de credenciamento não foram fornecidos a esta execução.
5. Operações de escrita permanecem fora do escopo do cliente novo. No pipeline genérico, retries automáticos são limitados a GET/HEAD/OPTIONS; os envios de arquivos não têm retry HTTP automático.
