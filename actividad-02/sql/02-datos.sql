SET NAMES utf8mb4;
INSERT INTO categorias (id,nombre,descripcion) VALUES
(1,'Miel','Miel pura de la Chiquitanía'),(2,'Derivados','Polen, propóleo y productos de la colmena'),(3,'Cera','Cera y productos elaborados');
INSERT INTO socios (id,nombre,comunidad,telefono) VALUES
(1,'Rosa Chuvé','San Javier','71234501'),(2,'Mario Surubí','Concepción','71234502'),(3,'Elena Masabí','San Ignacio de Velasco',NULL),
(4,'Julián Pesoa','San Rafael','71234504'),(5,'Carmen Tomichá','Lomerío','71234505'),(6,'Hernán Parapaino','San Miguel de Velasco',NULL),
(7,'Luisa Poñé','Santa Ana de Velasco','71234507'),(8,'Andrés Chuvirú','Roboré',NULL);
INSERT INTO productos (codigo,nombre,categoria_id,precio,stock,socio_id) VALUES
('MIE-500','Miel multifloral 500 g',1,45.00,32,1),('MIE-1K','Miel multifloral 1 kg',1,85.00,18,1),
('MIE-CUS','Miel de cusi 350 g',1,55.50,0,2),('POL-250','Polen & propóleo (pack 250 g)',2,70.00,9,3),
('PRO-30','Propóleo en gotas 30 ml',2,38.00,25,3),('CER-1K','Cera pura 1 kg',3,120.00,4,2),
('MIE-250','Miel multifloral 250 g',1,25.00,40,4),('MIE-2K','Miel multifloral 2 kg',1,160.00,6,4),
('MIE-SMO','Miel de bosque ahumada 500 g',1,58.00,11,5),('MIE-CAM','Miel de campo 750 g',1,65.00,14,6),
('MIE-OR','Miel orgánica certificada 500 g',1,72.00,8,7),('MIE-SAC','Miel en sachet (caja x 20)',1,48.00,22,8),
('MIE-PAN','Miel en panal 400 g',1,95.00,3,5),('MIE-JEN','Miel con jengibre 350 g',1,52.00,17,6),
('MIE-LIM','Miel con limón 350 g',1,52.00,0,7),('MIE-TOC','Miel de tajibo 500 g',1,68.00,9,4),
('POL-100','Polen seco 100 g',2,32.50,15,3),('POL-500','Polen seco 500 g',2,140.00,5,5),
('PRO-60','Propóleo en gotas 60 ml',2,65.00,12,3),('PRO-SPR','Spray de propóleo 30 ml',2,45.00,20,6),
('JAL-30','Jalea real 30 g',2,110.00,4,7),('JAB-MIE','Jabón de miel y avena',2,18.00,35,8),
('JAB-PRO','Jabón de propóleo',2,18.00,28,8),('CRE-MIE','Crema de manos con miel 60 g',2,35.00,16,5),
('HID-500','Hidromiel artesanal 500 ml',2,55.00,10,4),('CAR-MIE','Caramelos de miel (bolsa)',2,12.00,60,6),
('VIN-MIE','Vinagre de miel 500 ml',2,30.00,0,7),('CER-500','Cera pura 500 g',3,65.00,7,4),
('CER-LAM','Láminas de cera estampada (x10)',3,85.00,12,6),('VEL-CER','Velas de cera (par)',3,28.00,25,5),
('CER-COS','Cera cosmética 250 g',3,42.00,9,8),('BAL-LAB','Bálsamo labial de cera',3,15.00,40,7),
('CER-ENV','Envoltorio de cera para alimentos (x3)',3,60.00,13,5),('CER-BLO','Bloque de cera 5 kg',3,540.00,1,2),
('VEL-ARO','Vela aromática de cera 200 g',3,38.00,0,6),('CER-MAD','Cera para madera 250 g',3,45.00,11,4);
