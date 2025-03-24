import React, { useEffect, useState } from 'react';
import { FlatList, StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';

export default function interfaz_warehouse({ navigation }) {
  const [warehouses, setWarehouses] = useState([]);
  const [groupedWarehouses, setGroupedWarehouses] = useState([]); // Agrupar en pares para tener buen estilo
  const [menuVisible, setMenuVisible] = useState(false); // Controla la visibilidad del menú
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
      console.error('Error obteniendo Categorias:');
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
      console.error('Error obteniendo Ordenes:');
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
      console.error('Error obteniendo materiales recibidos:');
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
      console.error('Error obteniendo sub almacenes:');
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
      console.error('Error obteniendo supplys:');
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
      console.error('Error obteniendo supplier:');
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
      console.error('Error obteniendo transaciones:');
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
      console.error('Error obteniendo hardware:');
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
      console.error('Error obteniendo component:');
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
      console.error('Error obteniendo fisicos:');
  }
};

//console.log(warehouses);

return (
  <View style={styles.container}>
    {/* Menú tipo hamburguesa */}
    <TouchableOpacity
      style={styles.menuButton}
      onPress={() => setMenuVisible(!menuVisible)} // Alterna la visibilidad del menú
    >
      <Icon name="menu" size={30} color="white" />
    </TouchableOpacity>

    {menuVisible && (
      <View style={styles.menu}>
        <TouchableOpacity
          style={styles.menuItem}
          onPress={() => {
            setMenuVisible(false);
            navigation.navigate('CreateWarehouse'); // Navega a la pantalla para añadir un almacén
          }}
        >
          <Text style={styles.menuText}>Añadir Almacén</Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={styles.menuItem}
          onPress={() => {
            setMenuVisible(false);
            navigation.navigate('UpdateWarehouse'); // Navega a la pantalla para actualizar un almacén
          }}
        >
          <Text style={styles.menuText}>Actualizar Almacén</Text>
        </TouchableOpacity>
      </View>
    )}

    <FlatList
      style={styles.list}
      data={groupedWarehouses} // Usamos la lista agrupada
      renderItem={({ item }) => (
        <View style={styles.row}>
          <View style={styles.card}>
            <Text
              style={styles.cardTitle}
              onPress={() =>
                navigation.navigate('WarehouseDetails', {
                  id: item[0]?.id_warehouse,
                  name: item[0]?.name,
                })
              }
            >
              {item[0]?.name}
            </Text>
            <Text style={styles.cardSubtitle}>Capacidad: {item[0]?.capacity}</Text>
            <Text style={styles.cardSubtitle}>Ubicación: {item[0]?.location}</Text>
          </View>

          {item[1] && (
            <View style={styles.card}>
              <Text
                style={styles.cardTitle}
                onPress={() =>
                  navigation.navigate('WarehouseDetails', {
                    id: item[1]?.id_warehouse,
                    name: item[1]?.name,
                  })
                }
              >
                {item[1]?.name}
              </Text>
              <Text style={styles.cardSubtitle}>Capacidad: {item[1]?.capacity}</Text>
              <Text style={styles.cardSubtitle}>Ubicación: {item[1]?.location}</Text>
            </View>
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
menuButton: {
  position: 'absolute',
  top: 20,
  right: 20,
  zIndex: 10,
  backgroundColor: 'rgb(42, 126, 209)',
  borderRadius: 50,
  padding: 10,
},
menu: {
  position: 'absolute',
  top: 70,
  right: 20,
  backgroundColor: 'white',
  borderRadius: 10,
  padding: 10,
  zIndex: 10,
  shadowColor: '#000',
  shadowOffset: { width: 0, height: 2 },
  shadowOpacity: 0.3,
  shadowRadius: 5,
  elevation: 5,
},
menuItem: {
  paddingVertical: 10,
  paddingHorizontal: 20,
},
menuText: {
  fontSize: 16,
  color: 'rgb(33, 37, 41)',
},
row: {
  flexDirection: 'row',
  justifyContent: 'space-between',
  alignItems: 'center',
  marginBottom: 20,
},
card: {
  flex: 1,
  backgroundColor: 'white',
  borderRadius: 15,
  padding: 20,
  marginHorizontal: 10,
  shadowColor: '#000',
  shadowOffset: { width: 0, height: 2 },
  shadowOpacity: 0.3,
  shadowRadius: 5,
  elevation: 6,
},
cardTitle: {
  fontSize: 20,
  fontWeight: 'bold',
  color: 'rgb(42, 126, 209)',
  marginBottom: 10,
  textAlign: 'center',
},
cardSubtitle: {
  fontSize: 16,
  color: 'rgb(33, 37, 41)',
  marginBottom: 5,
  textAlign: 'center',
},
});