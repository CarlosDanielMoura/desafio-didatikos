function formatarValorEmReais(valor) {
  const opcoesFormato = {
    style: "currency",
    currency: "BRL",
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  };

  try {
    const formatoReais = new Intl.NumberFormat("pt-BR", opcoesFormato);
    return formatoReais.format(valor);
  } catch (error) {
    console.error("Erro ao formatar o valor:", error.message);
    return "Valor inválido";
  }
}

export const modules = formatarValorEmReais;
