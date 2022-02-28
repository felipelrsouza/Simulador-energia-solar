# Simulador de Orçamento de Sistema de Energia Solar

Criado para oferecer a simulação de orçamento para implantação e economia de um sistema de geração com placas solares. Originalmente planejado para compor o site da BELUGA Engenharia como landing page. 

O usuário insere os dados de consumo do imóvel, que é registrada no banco de dados em SQL e processada em PHP para o cálculo do orçameno estimado.

São utilizados duas páginas principais, de entrada e de resultados, escritas principalmente em HTML e CSS e responsivas. Optou-se por separar em dois arquivos PHP, de forma a faciltiar a comprensão do código. A transmissão de dados entre as páginas é feito pelo método POST.

Os maiores desafios no backend ficaram por conta do cálculo correto da estimativa, que envolve tarifas de concessionárias e irradiação solar em diversas cidades, e na validação dos dados inseridos. Enquanto que o frontend teve como desafio a criação do gráfico para diversos tamanhos de tela.

Em caso de dúvidas, sugestões, ou outros comentários, entrar em contato em felipelrsouza@outlook.com.
