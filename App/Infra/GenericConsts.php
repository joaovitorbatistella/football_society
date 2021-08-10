<?php

namespace Infra;

abstract class GenericConsts
{
    /* REQUESTS */
    public const REQUEST_TYPES = ['GET', 'POST', 'DELETE', 'PUT'];
    public const GET_TYPE = ['USERS', 'ATTENDANCE'];
    public const POST_TYPE = ['USERS', 'ATTENDANCE'];
    public const DELETE_TYPE = ['USERS', 'ATTENDANCE'];
    public const PUT_TYPE = ['USERS', 'ATTENDANCE'];

    /* ERRORS */
    public const MSG_ERROR_ROUTER_TYPE = 'Rota não permitida!';
    public const MSG_ERRO_RECURSO_INEXISTENTE = 'Recurso inexistente!';
    public const MSG_ERRO_GENERICO = 'Algum erro ocorreu na requisição!';
    public const MSG_ERRO_SEM_RETORNO = 'Nenhum registro encontrado!';
    public const MSG_ERRO_NAO_AFETADO = 'Nenhum registro afetado!';
    public const MSG_ERRO_TOKEN_VAZIO = 'É necessário informar um Token!';
    public const MSG_ERRO_TOKEN_NAO_AUTORIZADO = 'Token não autorizado!';
    public const MSG_ERR0_JSON_VAZIO = 'O Corpo da requisição não pode ser vazio!';

    /* SUCESSO */
    public const MSG_DELETADO_SUCESSO = 'Registro deletado com Sucesso!';
    public const MSG_ATUALIZADO_SUCESSO = 'Registro atualizado com Sucesso!';

    /* RECURSO USUARIOS */
    public const MSG_ERRO_ID_OBRIGATORIO = 'ID é obrigatório!';
    public const MSG_ERRO_LOGIN_EXISTENTE = 'Login já existente!';
    public const MSG_ERRO_LOGIN_SENHA_OBRIGATORIO = 'Login e Senha são obrigatórios!';

    /* RETORNO JSON */
    const SUCCESS_TYPE = 'sucesso';
    const ERROR_TYPE = 'erro';

    /* OUTRAS */
    public const SIM = 'S';
    public const TYPE = 'tipo';
    public const RESPONSE = 'resposta';
}