import React, { useState, useEffect } from "react";
import { View, Text, TextInput, Button, Alert, StyleSheet } from "react-native";
import { Picker } from "@react-native-picker/picker"; // Importar Picker
import { BASE_URL } from "C:/xampp/htdocs/PROYECTO4B-1/REACT/Interfaz/config";

export default function CreateSupplyScreen({ navigation }) {
  const [orders, setOrders] = useState([]); // Lista de órdenes
  const [suppliers, setSuppliers] = useState([]); // Lista de proveedores
  const [statuses, setStatuses] = useState([]); // Lista de estados
  const [selectedOrder, setSelectedOrder] = useState(""); // Orden seleccionada
  const [quantity, setQuantity] = useState(""); // Cantidad
  const [selectedSupplier, setSelectedSupplier] = useState(""); // Proveedor seleccionado
  const [selectedStatus, setSelectedStatus] = useState(""); // Estado seleccionado

  // Cargar datos desde la API
  useEffect(() => {
    const fetchData = async () => {
      try {
        const ordersResponse = await fetch(
          `${BASE_URL}PROYECTO4B-1/phpfiles/react/order_api.php`
        );
        if (!ordersResponse.ok) {
          const errorText = await ordersResponse.text(); // Leer la respuesta como texto
          throw new Error(
            `Error en el servidor: ${ordersResponse.status} - ${errorText}`
          );
        }
        const ordersData = await ordersResponse.json();
        setOrders(ordersData);

        const suppliersResponse = await fetch(
          `${BASE_URL}PROYECTO4B-1/phpfiles/react/supplier_api.php`
        );
        if (!suppliersResponse.ok) {
          const errorText = await suppliersResponse.text();
          throw new Error(
            `Error en el servidor: ${suppliersResponse.status} - ${errorText}`
          );
        }
        const suppliersData = await suppliersResponse.json();
        setSuppliers(suppliersData);

        const statusesResponse = await fetch(
          `${BASE_URL}PROYECTO4B-1/phpfiles/react/estatusApi.php`
        );
        if (!statusesResponse.ok) {
          const errorText = await statusesResponse.text();
          throw new Error(
            `Error en el servidor: ${statusesResponse.status} - ${errorText}`
          );
        }
        const statusesData = await statusesResponse.json();
        setStatuses(statusesData);
      } catch (error) {
        console.error("Error al cargar los datos:", error.message);
        Alert.alert("Error", error.message);
      }
    };

    fetchData();
  }, []);

  const handleSubmit = async () => {
    // Validar los datos
    if (!selectedOrder || !quantity || !selectedSupplier || !selectedStatus) {
      Alert.alert("Error", "Por favor, completa todos los campos.");
      return;
    }

    const payload = {
      id_order: parseInt(selectedOrder),
      quantity: parseInt(quantity),
      id_supplier: parseInt(selectedSupplier),
      id_status: parseInt(selectedStatus),
    };

    try {
      // Enviar los datos al backend
      const response = await fetch(
        `${BASE_URL}PROYECTO4B-1/phpfiles/react/supply_api.php`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        }
      );

      if (!response.ok) {
        // Leer la respuesta completa del servidor
        const errorText = await response.text();
        console.error("Respuesta del servidor:", errorText);
        throw new Error(
          `Error en el servidor: ${response.status} - ${errorText}`
        );
      }

      Alert.alert("Éxito", "Suministro creado con éxito");
      navigation.goBack(); // Regresa a la pantalla anterior
    } catch (error) {
      console.error("Error al crear el suministro:", error.message);
      Alert.alert("Error", `Error al crear el suministro: ${error.message}`);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Crear Nuevo Suministro</Text>

      <Text style={styles.label}>Orden:</Text>
      <Picker
        selectedValue={selectedOrder}
        onValueChange={(itemValue) => setSelectedOrder(itemValue)}
        style={styles.picker}
      >
        <Picker.Item label="Seleccionar orden" value="" />
        {orders.map((order) => (
          <Picker.Item
            key={order.id_order}
            label={`Orden #${order.id_order}`}
            value={order.id_order}
          />
        ))}
      </Picker>

      <Text style={styles.label}>Cantidad:</Text>
      <TextInput
        style={styles.input}
        keyboardType="numeric"
        value={quantity}
        onChangeText={setQuantity}
        placeholder="Ingresa la cantidad"
        placeholderTextColor="gray"
      />

      <Text style={styles.label}>Proveedor:</Text>
      <Picker
        selectedValue={selectedSupplier}
        onValueChange={(itemValue) => setSelectedSupplier(itemValue)}
        style={styles.picker}
      >
        <Picker.Item label="Seleccionar proveedor" value="" />
        {suppliers.map((supplier) => (
          <Picker.Item
            key={supplier.id_supplier}
            label={supplier.name}
            value={supplier.id_supplier}
          />
        ))}
      </Picker>

      <Text style={styles.label}>Estado:</Text>
      <Picker
        selectedValue={selectedStatus}
        onValueChange={(itemValue) => setSelectedStatus(itemValue)}
        style={styles.picker}
      >
        <Picker.Item label="Seleccionar estado" value="" />
        {statuses.map((status) => (
          <Picker.Item
            key={status.id_status}
            label={status.description}
            value={status.id_status}
          />
        ))}
      </Picker>

      <Button title="Crear Suministro" onPress={handleSubmit} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    backgroundColor: "rgb(33, 37, 41)",
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    color: "white",
    marginBottom: 16,
    textAlign: "center",
  },
  label: {
    fontSize: 16,
    fontWeight: "bold",
    color: "white",
    marginTop: 10,
  },
  input: {
    backgroundColor: "white",
    borderRadius: 10,
    padding: 10,
    marginBottom: 20,
    fontSize: 16,
    color: "black",
  },
  picker: {
    height: 50,
    backgroundColor: "white",
    marginVertical: 10,
    borderWidth: 1,
    borderColor: "#CCC",
    borderRadius: 5,
  },
});
