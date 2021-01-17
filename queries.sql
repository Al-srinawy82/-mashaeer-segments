
-- SELECT (ST_DumpPoints(geom)).path as path, gid, (ST_DumpPoints(geom)).geom FROM public.mashaeer_street order by gid asc;
-- select gid, ST_X(ST_Centroid(ST_Transform(ST_PointN(the_geom,1), 4326))) AS long, ST_Y(ST_Centroid(ST_Transform(ST_PointN(the_geom,1), 4326))) AS lat from (SELECT gid, (ST_Dump(geom2d)).geom As the_geom FROM public.mashaeer_street) As foo

-- INSERT INTO public.mashaeer_nodes (gid, geom, node_path, node_sequence)
-- SELECT gid, (ST_DumpPoints(geom)).geom, (ST_DumpPoints(geom)).path, NULL
-- FROM public.mashaeer_street

-- ALTER TABLE public.mashaeer_nodes ADD geom2d geometry;
-- UPDATE public.mashaeer_nodes SET geom2d = ST_Force2D(geom);


 UPDATE public.mashaeer_nodes SET long = ST_X(ST_Centroid(ST_Transform(geom2d, 4326))),lat = ST_y(ST_Centroid(ST_Transform(geom2d, 4326)));


-- SELECT *,ST_X(ST_Centroid(ST_Transform(geom2d, 4326))), ST_y(ST_Centroid(ST_Transform(geom2d, 4326))) FROM public.mashaeer_nodes order by gid asc

