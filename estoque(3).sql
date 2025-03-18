

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `item`
--

CREATE TABLE `item` (
  `id_item` int(11) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `unidade` varchar(50) NOT NULL,
  `fk_Categoria_id_categoria` int(11) NOT NULL,
  `estoqueCritico` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `item`
--


-- --------------------------------------------------------

--
-- Estrutura para tabela `log_movimentacao`
--

CREATE TABLE `log_movimentacao` (
  `id_log` int(11) NOT NULL,
  `fk_item_id` int(11) NOT NULL,
  `fk_usuario_id` int(11) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `quantidade` int(11) NOT NULL,
  `validade` date DEFAULT NULL,
  `data_log` timestamp NOT NULL DEFAULT current_timestamp(),
  `descricao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacao`
--

CREATE TABLE `movimentacao` (
  `id_movimentacao` int(11) NOT NULL,
  `fk_item_id` int(11) NOT NULL,
  `fk_usuario_id` int(11) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `quantidade` int(11) NOT NULL CHECK (`quantidade` > 0),
  `data_movimento` timestamp NOT NULL DEFAULT current_timestamp(),
  `validade` date DEFAULT NULL,
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `movimentacao`
--


-- --------------------------------------------------------

--
-- Estrutura para tabela `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `role`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `login` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `fk_Role_id_role` int(11) NOT NULL,
  `siape` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--


--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `fk_Categoria_id_categoria` (`fk_Categoria_id_categoria`);

--
-- Índices de tabela `log_movimentacao`
--
ALTER TABLE `log_movimentacao`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_item_id` (`fk_item_id`),
  ADD KEY `fk_usuario_id` (`fk_usuario_id`),
  ADD KEY `data_log` (`data_log`);

--
-- Índices de tabela `movimentacao`
--
ALTER TABLE `movimentacao`
  ADD PRIMARY KEY (`id_movimentacao`),
  ADD KEY `fk_movimentacao_item` (`fk_item_id`),
  ADD KEY `fk_movimentacao_usuario` (`fk_usuario_id`);

--
-- Índices de tabela `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `fk_Role_id_role` (`fk_Role_id_role`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=494;

--
-- AUTO_INCREMENT de tabela `log_movimentacao`
--
ALTER TABLE `log_movimentacao`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `movimentacao`
--
ALTER TABLE `movimentacao`
  MODIFY `id_movimentacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=499;

--
-- AUTO_INCREMENT de tabela `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`fk_Categoria_id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Restrições para tabelas `log_movimentacao`
--
ALTER TABLE `log_movimentacao`
  ADD CONSTRAINT `log_movimentacao_ibfk_1` FOREIGN KEY (`fk_item_id`) REFERENCES `item` (`id_item`),
  ADD CONSTRAINT `log_movimentacao_ibfk_2` FOREIGN KEY (`fk_usuario_id`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `movimentacao`
--
ALTER TABLE `movimentacao`
  ADD CONSTRAINT `fk_movimentacao_item` FOREIGN KEY (`fk_item_id`) REFERENCES `item` (`id_item`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_movimentacao_usuario` FOREIGN KEY (`fk_usuario_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`fk_Role_id_role`) REFERENCES `role` (`id_role`);
COMMIT;

INSERT INTO `role` (`id_role`, `nome`) VALUES
(1, 'Administrador'),
(2, 'Estoquista'),
(3, 'Professor');

INSERT INTO `usuario` (`id_usuario`, `nome`, `login`, `senha`, `fk_Role_id_role`, `siape`) VALUES
(7, 'Fab Quevedo', 'adm', '$2y$10$AUpWF3P/7Np13QqanNjcJ.LlCNACWAYlLoUyr/UecUac8e7ZP4HPC', 1, '109109120'),
(8, 'Henrico Iglesias', 'estoquista', '$2y$10$qy.yCK0sXZK7S/Q5KP16nuEWtQnCwI4iSgkZ0PrniiXTrgc7O3Qba', 2, '12093098');