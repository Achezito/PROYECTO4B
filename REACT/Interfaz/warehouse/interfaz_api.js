import React, { useEffect, useState } from 'react';
import { FlatList, StyleSheet, Text, View } from 'react-native';

// API_URL es la direccion del archivo que se comunica con el model de la tabla para mandar el json (hice la carpeta react en phpFiles para poner ahi esos archivos)

export default function interfaz_warehouse({ navigation}) {
  console.log('Navigation:', navigation); // Verifica si navigation está disponible
  const [warehouses, setWarehouses] = useState([]);
  const [groupedWarehouses, setGroupedWarehouses] = useState([]); // agrupar en pares para tener buen estilo

  useEffect(() => { getWarehouses(); }, []); // El use effect es para mandar a llamar los datos al cargar la pantalla
  useEffect(() => { getCategories(); }, []);
  useEffect(() => { getOrders(); }, []);
  useEffect(() => { getReceivedMaterial(); }, []);
  useEffect(() => { getSub_warehouse(); }, []);
  useEffect(() => { getSupply(); }, []);
  useEffect(() => { getSupplier(); }, []);
  useEffect(() => { getTransaction(); }, []);
  useEffect(() => { getHardware(); }, []);
  useEffect(() => { getComponent(); }, []);
  useEffect(() => { getPhysical(); }, []);

  const getWarehouses = async () => {
    try {
        const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/warehouse_api.php", {
            method: 'GET'
        });
        const data = await response.json();
        console.log("Almacenes ",data);
        setWarehouses(data);

        // Toma de 2 en 2
        const groupedData = [];
        for (let i = 0; i < data.length; i += 2) {
          groupedData.push(data.slice(i, i + 2));
        }

        setGroupedWarehouses(groupedData);

    } catch (error) {
        console.error('Error obteniendo almacenes:', error);
    }
};

const getCategories = async () => {
  try {
      const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/category_api.php", {
        method: 'GET'
      });
      const data = await response.json();
      console.log("Categorias ",data);
  } catch (error) {
      console.error('Error obteniendo almacenes:');
  }
};

const getOrders = async () => {
  try {
      const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php", {
        method: 'GET'
      });
      const data = await response.json();
      console.log("Ordenes ",data);
  } catch (error) {
      console.error('Error obteniendo almacenes:');
  }
};

const getReceivedMaterial = async () => {
  try {
      const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/received_material_api.php", {
        method: 'GET'
      });
      const data = await response.json();
      console.log("Materiales recibidos ",data);
  } catch (error) {
      console.error('Error obteniendo almacenes:');
  }
};


const getSub_warehouse =  async () => {
  try {
      const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/received_material_api.php", {
        method: 'GET'
      });
      const data = await response.json();
      console.log("subwarehouse",data);
  } catch (error) {
      console.error('Error obteniendo almacenes:');
  }
};

const getSupply =  async () => {
  try {
      const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/supply_api.php", {
        method: 'GET'
      });
      const data = await response.json();
      console.log("supply ",data);
  } catch (error) {
      console.error('Error obteniendo almacenes:');
  }
};

const getSupplier =  async () => {
  try {
      const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/supplier_api.php", {
        method: 'GET'
      });
      const data = await response.json();
      console.log("proveedor   ",data);
  } catch (error) {
      console.error('Error obteniendo almacenes:');
  }
};

const getTransaction =  async () => {
  try {
      const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/transaction_api.php", {
        method: 'GET'
      });
      const data = await response.json();
      console.log("Transacciones   ",data);
  } catch (error) {
      console.error('Error obteniendo almacenes:');
  }
};

const getHardware =  async () => {
  try {
      const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/material_hardware_api.php", {
        method: 'GET'
      });
      const data = await response.json();
      console.log("Hardware   ",data);
  } catch (error) {
      console.error('Error obteniendo almacenes:');
  }
};

const getComponent =  async () => {
  try {
      const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/material_component_api.php", {
        method: 'GET'
      });
      const data = await response.json();
      console.log("Component   ",data);
  } catch (error) {
      console.error('Error obteniendo almacenes:');
  }
};

const getPhysical =  async () => { 
  try {
      const response = await fetch("http://localhost/PROYECTO4B-1/phpfiles/react/material_physical_api.php", {
        method: 'GET'
      });
      const data = await response.json();
      console.log("Physical   ",data);
  } catch (error) {
      console.error('Error obteniendo almacenes:');
  }
};

//console.log(warehouses);

return (
  <View style={styles.container}>
    <FlatList
      style={styles.list}
      data={groupedWarehouses} // Usamos la lista agrupada
      renderItem={({ item }) => (
        <View style={styles.row}>
          <Text
            style={[styles.labelText, { backgroundColor: 'rgb(42, 126, 209)' }]}
            onPress={() => navigation.navigate('WarehouseDetails', { id: item[0]?.id_warehouse, name: item[0]?.name })}
          >
            {item[0]?.name}
          </Text>


          {item[1] && (
            <Text
              style={[styles.labelText, { backgroundColor: 'rgb(42, 126, 209)' }]}
              onPress={() => navigation.navigate('WarehouseDetails', { id: item[1]?.id_warehouse, name: item[1]?.name })}
            >
              {item[1]?.name}
            </Text>
          )}
        </View>
      )}
      keyExtractor={(item, index) => index.toString()}
    />
  </View>
  );

}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'rgb(33, 37, 41)',
    padding: 20,
  },
  labelText: {
    height: 150,
    width: 150,
    borderRadius: 30,
    justifyContent: 'center',
    alignItems: 'center',
    display: 'flex',
    textAlign: 'center',
    lineHeight: 50,
    fontSize: "18pt"
  },
  row: {
    flexDirection: 'row',  // Cambié a 'row' para que los elementos estén en fila.
    justifyContent: 'center',
    alignItems: 'center',
    gap: 30,  // Opcional: Para agregar espacio entre los elementos en la fila
    marginBottom: 30,
    marginTop: 10
  }
});
