# export with semicolon as delimeter

# german main categories
SET @rank=0;
select m.id as categoryId, m.name as description, m.name as metatitle, 3330001 as parentID, @rank:=@rank+1 AS position from manufacturer as m order by m.name;

### php bin/console sw:importexport:import files/import_export/manufacturer.csv -p default_categories -f csv -vvv

# german sub categories
select v.id as categoryId, v.name, m.id as parentID from manufacturer as m
left join vehicle as v on v.manufacturer_id = m.id
order by m.id, v.name;

# german product concretes
select b.id, m.name, v.id, b.slug from manufacturer as m
left join vehicle as v on v.manufacturer_id = m.id
left join battery_usage as bu on bu.vehicle_id = v.id
left join battery as b on bu.battery_id = b.id;

# articles with categories
select distinct battery.id as additionalText, availability.id as ordernumber, availability.id as mainnumber, availability.name, CAST(availability.price/100 as DECIMAL(10,2)) price, availability.supplier_id as supplier, battery.slug as description, battery_usage.vehicle_id as categories, 19 as tax, 1 as active from battery
left join battery_usage on battery_usage.battery_id = battery.id and battery_usage.vehicle_id > 1 and EXISTS(select 1 FROM vehicle WHERE vehicle.id = battery_usage.vehicle_id and vehicle.slug IS NOT NULL)
left join `availability` on availability.name = battery.name and availability.voltage = battery.voltage
WHERE availability.id != '' and availability.price IS NOT NULL and availability.name IS NOT NULL limit 5000;


select distinct battery.id as additionalText, availability.id as ordernumber, availability.id as mainnumber, availability.name, CAST(availability.price/100 as DECIMAL(10,2)) price, availability.supplier_id as supplier, battery.slug as description, 19 as tax, 1 as active from battery
left join `availability` on availability.name = battery.name and availability.voltage = battery.voltage
WHERE availability.id != '' and availability.price IS NOT NULL and availability.name IS NOT NULL limit 5000;