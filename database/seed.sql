-- Monitrade website — initial content migrated from monitrade.org
-- Import after schema.sql. No admin account is created here:
-- open /admin/setup.php once to create the first administrator.

SET NAMES utf8mb4;

-- ---------------------------------------------------------------- settings
INSERT INTO settings (`key`, `value`) VALUES
('company_name',   'Monitrade'),
('tagline',        'Agent & Service Provider for the Textile Industry'),
('founded_year',   '1989'),
('chairman',       'Eng. Ihab Mounir'),
('company_email',  'monitrade@monitrade.net'),
('cairo_address',  '114 Omar Ibn El Khattab St., Heliopolis, Cairo, Egypt'),
('cairo_phone',    '+20 2 2291 6457 / +20 2 2417 3734'),
('cairo_fax',      '+20 2 2290 4359'),
('cairo_mobile',   '+20 100 163 0386'),
('alex_address',   'Mostafa Kamel Armed Forces Building No. 4, Floor 11, Flat 112, Mostafa Kamel, Alexandria, Egypt'),
('alex_phone',     '+20 3 546 8536'),
('alex_fax',       '+20 3 545 7121'),
('alex_mobile',    '+20 106 884 5414'),
('home_intro',     'Since its start in 1989, Monitrade has sold hundreds of Truetzschler, Zinser and Schlumberger machines in Egypt and millions of Euros worth of spare parts. All business partners who are and were part of the Monitrade family enjoyed quality customer service to their clients by our staff.\n\nMonitrade has a reliable distribution network that reaches almost all Egyptian industrial cities to ensure speedy delivery of spare and consumable parts to our customers. Monitrade has warehouses in Cairo and Alexandria to keep consumable parts for the convenience of our customers in urgent times. For customers who prefer to deal in Egyptian pounds, Monitrade will buy the product in foreign currency and resell it to the customer at the equivalent price in Egyptian pounds. In short, we will go to any length to provide our customers with the best sales experience and solve almost any problem they might have.\n\nOur sales personnel are also experts in the textile field: Mr. Nabil, our senior salesman, holds a postgraduate diploma in Textiles from Leeds University, UK, and has more than 35 years of experience with textile industrial problems.\n\nMonitrade has two offices — one in Cairo and one in Alexandria — to ensure that we are always reachable for our clients. We also start our sales cycle early by being present at major textile exhibitions, whether international (ITMA) or with our own booth at local ones (ITCE).'),
('profile_who',    'Monitrade is a leading textile agency located in Egypt. Since 1989 Monitrade has been dealing with the biggest names in the industry, serving them and looking after their needs.\n\nAlmost no company in the Egyptian spinning industry hasn''t dealt with Monitrade — representing highly trusted brand names in the industry like Bräcker, N. Schlumberger and many more.'),
('profile_why',    'Monitrade always serves you on time.\nMonitrade understands the Egyptian textile industry.\nMonitrade provides solutions, not only sales.\nMonitrade cares about your profitability.\nMonitrade is here to stay.\nMonitrade is here to grow.');

-- ---------------------------------------------------------------- team
INSERT INTO team_members (name, title, phone, email, sort_order) VALUES
('Eng. Ihab Mounir',      'Chairman',                        '+20 100 199 0306', '', 1),
('Eng. Mohamed Naeem',    'Technical Manager — Cairo',       '+20 100 399 4228', '', 2),
('Eng. Emad El-Dessouki', 'Technical Manager — Alexandria',  '+20 100 191 0324', '', 3);

-- ---------------------------------------------------------------- products
INSERT INTO products (section, category, name, slug, model, specs, offer, image, sort_order) VALUES
('machines', 'spinning', 'Long Staple Spinning Lines', 'long-staple-spinning-lines-nsc', 'NSC Fibre to Yarn',
 'NSC fibre to yarn teams design and manufacture a wide range of industrial machinery which produces yarn from natural or synthetic fibres.',
 NULL, 'products/nsc-fibre-to-yarn.jpg', 1),
('machines', 'spinning', 'Long Staple Spinning Lines', 'long-staple-spinning-lines-finlane', 'Finlane Group',
 'The Finlane group includes three leading companies, Sant''Andrea Novara, Cognetex and Seydel — hence its leading position at global level in the manufacturing of textile machines for the long staple market. Within a common framework, each company preserves its identity, acquired over the past decades. However, the group co-ordinates and rationalises the manufacturing activities and commercial strategies of these three brands, thus favouring their synergy and taking advantage of their complementary offer.\n\nThe ultimate goal is to meet the international market needs as efficiently as possible by offering a complete range of products which cover any long staple process stage.',
 NULL, 'products/finlane-group.jpg', 2),
('machines', 'garments', 'Texpa Machines', 'texpa-machines', 'High End Manufacturing Lines for Home Textiles',
 'Based on roughly 50 years of experience in system design and production, and as a worldwide leader and producer of fully automatic systems for cutting, sewing, folding and packaging of home textiles, TEXPA offers customer-designed machines according to your individual needs.',
 NULL, 'products/texpa-machines.gif', 1),
('machines', 'garments', 'Non-Woven Production Machines', 'non-woven-production-machines', 'NSC Non-Woven',
 'NSC nonwoven designs and manufactures integrated production lines which produce textile fabrics in a continuous process.',
 NULL, 'products/nsc-non-woven.jpg', 2),
('machines', 'garments', 'Melco Amaya XT', 'melco-amaya-xt', 'Amaya XT',
 'The AMAYA XT professional embroidery machine is no ordinary multi-head system: its multi-task capabilities can produce like no other multi-head on the market. Today''s embroidery production demands are quite diverse, and AMAYA XT''s multi-task abilities and connectivity enable businesses to take full advantage of consumer demand.\n\nMelco understands that investing in professional embroidery equipment is the most important decision our clients will make for their businesses.',
 NULL, 'products/melco-amaya-xt.jpg', 3),
('accessories', 'spinning', 'Travellers', 'bracker-travellers', 'Bräcker Travellers',
 'Despite its very tiny size, the Bräcker steel traveller is one of the most important key components in ring spinning and twisting of coarse and fine yarn counts as well as compact yarns. Best and high performance can be achieved by selecting the right Bräcker traveller type. Traveller shape, cross wire section, weight and finish have a significant impact on the productivity of a ring spinning machine and the quality of the yarns.',
 NULL, 'products/bracker-travellers.gif', 1),
('accessories', 'spinning', 'Bräcker Cots', 'bracker-cots', 'Bräcker Cots',
 'Berkol top roller covers are technology components which have a direct influence on yarn quality and the running properties of a spinning mill. By choosing the right Berkol covers or cots one minimizes lap formation and yarn breaks. Productivity is thus optimised and production costs are reduced in an efficient manner.',
 NULL, 'products/bracker-cots.jpg', 2),
('accessories', 'spinning', 'Prosino Rings', 'prosino-rings', 'Prosino Rings',
 'Prosino is the manufacturer of top quality spinning rings for cotton and woollen systems. Prosino is also a premium provider for European and Asian OEMs alike.',
 NULL, 'products/prosino-rings.jpg', 3),
('accessories', 'spinning', 'Cans and Trolleys', 'rimtex-cans-and-trolleys', 'Rimtex',
 'Rimtex India has provided premium quality sliver handling systems at the most competitive prices since 1992. Rimtex has now expanded its production range to include hospitality products as well, providing proof of its success.',
 NULL, 'products/rimtex-cans-trolleys.gif', 4),
('accessories', 'spinning', 'Inspiron Flyers', 'inspiron-flyers', 'Inspiron Flyers',
 'Set up in 1975, InspirOn Engineering is the market leader in flyers. Distinguished by their design and high-speed performance, InspirOn flyers score over others due to competency developed in tool design, metallurgy, die-casting, dynamic balancing and surface finishing.\n\nInspirOn has a non-ferrous foundry, a CNC machine and fabrication shop, and manufactures components for the textile and engineering industries.',
 NULL, 'products/inspiron-flyers.jpg', 5),
('accessories', 'spinning', 'Spindles', 'parmar-spindles', 'Parmar Spindles',
 'Manufacturing and supplying spindles for ring spinning, doubling, twisting, up-twisting and winding machines in the textile industry — familiar to textile mills and original equipment manufacturers as a preferred supplier at the most competitive prices.',
 NULL, 'products/parmar-spindles.jpg', 6),
('accessories', 'spinning', 'Suessen Spindelfabrik', 'suessen-spindelfabrik', 'Suessen Spindelfabrik',
 'Innovative concepts and components enabling the production of high quality staple fibre yarns. Today more than 10% of the world''s cotton crop is spun with SUESSEN components.',
 NULL, 'products/suessen.gif', 7),
('accessories', 'spinning', 'General Spinning Lines Spares', 'general-spinning-lines-spares', 'Spare Parts',
 'Monitrade can supply all your needs for general spinning line spare parts, from blow-room to spinning.',
 NULL, 'products/spinning-spare-parts.jpg', 8);

-- ---------------------------------------------------------------- brands
INSERT INTO brands (name, description, sort_order) VALUES
('Trützschler',        'Spinning preparation machinery', 1),
('Zinser',             'Ring spinning & roving frames', 2),
('N. Schlumberger',    'Long staple spinning machinery', 3),
('Bräcker',            'Travellers & cots (Berkol)', 4),
('Suessen',            'Spinning components', 5),
('Finlane Group',      'Sant''Andrea Novara · Cognetex · Seydel', 6),
('NSC',                'Fibre to yarn & non-woven lines', 7),
('Prosino',            'Spinning rings', 8),
('Rimtex',             'Cans & trolleys', 9),
('InspirOn',           'Flyers', 10),
('Parmar',             'Spindles', 11),
('Texpa',              'Home textile manufacturing lines', 12),
('Melco',              'Embroidery machines', 13);

-- ---------------------------------------------------------------- news & articles
INSERT INTO posts (type, title, slug, summary, body, image, source, published_on, is_ticker) VALUES
('news', 'Yarn prices fell by 25% within 3 weeks', 'yarn-prices-fell-by-25-percent-within-3-weeks',
 'The head of the Chamber of Textile Industries says falling yarn prices signal relief for textile factories.',
 'Mohamed El-Morshedy, head of the Chamber of Textile Industries, said that the drop in local yarn prices — driven by the declines in yarn prices worldwide — signals relief for the crisis facing textile factories.\n\nEl-Morshedy confirmed that yarn prices have fallen by between 20% and 25% over the past three weeks.',
 NULL, 'Al Mal newspaper (translated from Arabic)', NULL, 1),
('news', 'Public Prosecutor confirms validity of the usufruct procedures for Indorama Shebin (formerly Shebin El Kom)', 'public-prosecutor-confirms-indorama-shebin-usufruct',
 'Shebin El Kom company was not sold and will return to the state when the contract ends.',
 'Eng. Mohsen El-Gilany, head of the Holding Company for Spinning and Weaving, commented on the complaint filed against him by Talaat El-Sadat regarding the alleged waste of public funds through selling the Shebin El Kom Spinning and Weaving Company below its value.\n\nHe said that an investigation took place in 2006 and the Public Prosecutor issued a decision confirming the validity of the usufruct procedures granted to the Indian investor.\n\nHe added that Shebin El Kom company was not sold: it is a 25-year usufruct right, after which the company returns to the state.\n\nThe past period has seen tension between the Indian board of directors and the workers, after the investor refused to meet the workers'' demands for higher wages and incentives, while the company threatened to resort to international arbitration to terminate its contract with the government if the sit-in continued.',
 NULL, 'Al Mal newspaper (translated from Arabic)', '2013-11-27', 1),
('article', 'Making Cotton Learning Journey', 'making-cotton-learning-journey',
 'Cotton, flax, wool and vegetable fibres were used for spinning. The earliest type of simple spinning was done by holding fibres in one hand, then twisting and pulling them with the other.',
 'Cotton is not made; it is a plant which grows in warmer parts of the world. Cotton plants have yellowish pink flowers which bloom very briefly. The seed-bearing part of the plant is known as the boll, and it is this which is picked to make cotton. For thousands of years cotton has been harvested, cleaned, spun, woven, bleached and dyed to make an astonishing variety of different items.\n\nCotton, flax, wool and vegetable fibres were used for spinning. The earliest type of simple spinning was done by holding fibres in one hand, then twisting and pulling them with the other hand.\n\nLater, drop spinning was introduced. This was done with a spindle (a tapered wooden stick) with a whorl (weight) at one end to help it spin. The spindle would be rolled (probably against the leg) to twist the fibres, and the weight on one end would help to stretch them.\n\nThe bead-whorl spindle was "specifically designed to spin fine yarns which require a lot of twist and was in widespread use throughout Asia, the Middle East and Africa where short staple fibres such as cashmere, cotton and camel were used" (Heather McCloy, 2000).\n\nDistaff spinning was done by holding (usually) long staple fibres, such as wool and flax, on a comb or in a cleft on a distaff (wooden stick), which was held by the left arm while the spindle and yarn feed were worked with the right hand.\n\nSpinning wheels are the best known tool for spinning. The Great Wheel, developed for spinning yarns (including cotton) in India around 500 B.C., was the first to have a mechanised spindle.\n\nThere were spinning wheels in Persia by 1257 and in China in 1270, but they were not in general use in Europe until the 14th century, when the Saxony Wheel, which used a foot pedal to turn the spindle, was introduced in Germany.\n\nSpinning (or spindle) wheels could spin without so much pulling and twisting of the fibre, which produced a softer yarn, but it was not as strong. Very little cotton yarn was spun in England because of the length of time it took.\n\nThe spinning jenny, invented in 1763, could initially spin six threads at once instead of just a single thread. This was later increased to 14 threads.\n\nThe spinning mule, however, could spin multiple threads that were of suitable strength for both warp (threads running down the weave) and weft (threads running across the weave).\n\nAfter these last two developments, cotton spinning became a major industry in Lancashire.\n\nTimeline\n• Drop spinning — Neolithic (c. 8,000–5,000 B.C.; 2,500 B.C. in England)\n• Distaff spinning — Medieval (c. A.D. 1066–1478)\n• Spinning wheels — 500 B.C. (India), A.D. 1400s (Europe)\n• Spinning jenny — A.D. 1763\n• Spinning mule — A.D. 1779',
 'posts/making-cotton.jpg', NULL, '2008-11-27', 0);
