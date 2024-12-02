INSERT INTO gs_an_table (name, mail, naiyou, indate) VALUES('test1', 'aaa@mail.com', 'ないよ！', sysdate());

INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('01', '集英社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('02', '翔泳社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('03', 'インプレス', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('04', '技術評論社', sysdate());

INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('05', 'SBクリエイティブ', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('06', '富士通ラーニングメディア', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('07', 'オライリー・ジャパン', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('08', 'エムディエヌコーポレーション', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('09', 'マイナビ出版', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('10', '秀和システム', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('11', 'PHP研究所', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('12', 'リックテレコム', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('13', 'ソーテック社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('14', '講談社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('15', '白泉社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('16', '小学館', sysdate());

INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('17', 'KADOKAWA', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('18', 'Gakken', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('19', '第三文明社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('20', '日本能率協会マネジメントセンター', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('21', '新潮社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('22', 'かんき出版', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('23', 'トライエックス', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('24', '扶桑社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('25', 'アリス館', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('26', '宝島社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('27', '文響社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('28', 'プレジデント社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('29', 'ブックマン社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('30', '大和書房', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('31', 'クレヴィス', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('32', '河出書房新社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('33', '玄光社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('34', 'ビジネス社', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('35', '文化学園　文化出版局', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('36', '文藝春秋', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('37', '学研教育出版', sysdate());
INSERT INTO gs_publisher (publish_cd, publish_name, indate) VALUES('38', '日経BP', sysdate());

INSERT INTO gs_lifeflg (life_flg, name) VALUES(0, '在職中');
INSERT INTO gs_lifeflg (life_flg, name) VALUES(1, '退職');
INSERT INTO gs_lifeflg (life_flg, name) VALUES(2, '休職中');

INSERT INTO gs_an_table (name, mail, naiyou, indate) VALUES(:name, :mail, :naiyou, sysdate());

SELECT * FROM gs_an_table;
SELECT id, name FROM gs_an_table;

SELECT * FROM gs_an_table WHERE id=2;